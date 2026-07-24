<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Schedule;

use PortalCMS\Features\Email\Entity\MailAttachment;
use PortalCMS\Features\Email\Entity\MailBatch;
use PortalCMS\Features\Email\Entity\MailSchedule as ScheduledMail;
use PortalCMS\Features\Email\Entity\MailTemplate;
use PortalCMS\Features\Email\Message\EmailMessage;
use PortalCMS\Features\Email\Recipient\EmailRecipient;
use PortalCMS\Features\Email\Repository\MailBatchRepository;
use PortalCMS\Features\Email\Repository\MailScheduleRepository;
use PortalCMS\Features\Email\Repository\MailTemplateRepository;
use PortalCMS\Features\Email\SMTP\SMTPConfiguration;
use PortalCMS\Features\Email\Transport\MailTransport;
use PortalCMS\Features\Members\Entity\Member;
use PortalCMS\Features\Members\Repository\MemberRepository;
use PortalCMS\Features\Settings\SiteSetting;

final class MailSchedule
{
    public function __construct(
        private readonly MailScheduleRepository $mails,
        private readonly MailBatchRepository $batches,
        private readonly MailTemplateRepository $templates,
        private readonly MemberRepository $members,
        private readonly SiteSetting $settings,
        private readonly MailTransport $transport,
        private readonly SMTPConfiguration $configuration,
    ) {
    }

    /**
     * @param EmailRecipient[] $recipients
     * @param MailAttachment[] $attachments
     */
    public function create(
        string $subject,
        string $body,
        array $recipients,
        array $attachments = [],
        ?int $batchId = null,
        ?int $memberId = null,
        ?int $createdBy = null,
    ): ScheduledMail {
        $mail = ScheduledMail::create($batchId, $memberId, $subject, $body, $createdBy);
        foreach ($recipients as $recipient) {
            $mail->addRecipient($recipient->email, $recipient->name);
        }
        foreach ($attachments as $attachment) {
            $mail->addAttachment(
                (string) $attachment->path,
                (string) $attachment->name,
                (string) $attachment->extension,
                $attachment->encoding ?? 'base64',
                $attachment->type ?? 'application/octet-stream',
            );
        }

        $this->mails->save($mail);
        $this->mails->flush();

        return $mail;
    }

    public function queue(ScheduledMail $mail): void
    {
        $this->mails->save($mail);
    }

    public function flush(): void
    {
        $this->mails->flush();
    }

    public function findDateSent(int $mailId): ?\DateTimeImmutable
    {
        $mail = $this->mails->find($mailId);
        return $mail instanceof ScheduledMail ? $mail->DateSent : null;
    }

    /**
     * @param int[] $mailIds
     * @return array{deleted: int, failed: int}
     */
    public function delete(array $mailIds): array
    {
        $result = [ 'deleted' => 0, 'failed' => 0 ];
        foreach (array_unique(array_map('intval', $mailIds)) as $mailId) {
            $mail = $this->mails->find($mailId);
            if (!$mail instanceof ScheduledMail) {
                ++$result['failed'];
                continue;
            }
            $this->mails->remove($mail);
            ++$result['deleted'];
        }
        if ($result['deleted'] > 0) {
            $this->mails->flush();
        }

        return $result;
    }

    /**
     * @param int[] $mailIds
     * @return array{sent: int, failed: int, alreadySent: int}
     */
    public function send(array $mailIds): array
    {
        $mails = [];
        $alreadySent = 0;
        $failed = 0;
        foreach (array_unique(array_map('intval', $mailIds)) as $mailId) {
            $mail = $this->mails->find($mailId);
            if (!$mail instanceof ScheduledMail) {
                ++$failed;
            } elseif (!$mail->isScheduled()) {
                ++$alreadySent;
            } else {
                $mails[] = $mail;
            }
        }

        $result = $this->sendScheduled($mails);
        $result['failed'] += $failed;
        $result['alreadySent'] += $alreadySent;

        return $result;
    }

    /**
     * @param ScheduledMail[] $mails
     * @return array{sent: int, failed: int, alreadySent: int}
     */
    public function sendScheduled(array $mails): array
    {
        $result = [ 'sent' => 0, 'failed' => 0, 'alreadySent' => 0 ];
        foreach ($mails as $mail) {
            if (!$mail->isScheduled()) {
                ++$result['alreadySent'];
                continue;
            }
            if ($this->sendOne($mail)) {
                ++$result['sent'];
            } else {
                ++$result['failed'];
            }
        }
        if ($mails !== []) {
            $this->mails->flush();
        }

        return $result;
    }

    /**
     * @param int[] $memberIds
     * @return array{created: int, failed: int, batchId: int|null}
     */
    public function createFromMemberTemplate(
        int $templateId,
        array $memberIds,
        ?int $createdBy = null,
    ): array {
        $template = $this->templates->find($templateId);
        if (!$template instanceof MailTemplate || $template->type !== 'member' || $memberIds === []) {
            return [ 'created' => 0, 'failed' => count($memberIds), 'batchId' => null ];
        }

        $batch = MailBatch::create($template->id, $createdBy);
        $this->batches->save($batch);
        $this->batches->flush();

        $result = [ 'created' => 0, 'failed' => 0, 'batchId' => $batch->id ];
        foreach (array_unique(array_map('intval', $memberIds)) as $memberId) {
            $member = $this->members->find($memberId);
            if (!$member instanceof Member || empty($member->emailadres)) {
                ++$result['failed'];
                continue;
            }

            $mail = ScheduledMail::create(
                $batch->id,
                $member->id,
                (string) $template->subject,
                $this->renderMemberTemplate($member, (string) $template->body),
                $createdBy,
            );
            $mail->addRecipient(
                $member->emailadres,
                trim((string) $member->voornaam . ' ' . (string) $member->achternaam),
            );
            foreach ($template->attachments() as $attachment) {
                $mail->copyAttachment($attachment);
            }
            $this->mails->save($mail);
            ++$result['created'];
        }
        $this->mails->flush();

        return $result;
    }

    private function sendOne(ScheduledMail $mail): bool
    {
        $recipients = array_map(
            static fn ($recipient): EmailRecipient => new EmailRecipient(
                $recipient->email,
                $recipient->name,
            ),
            $mail->recipients()->toArray(),
        );
        if ($recipients === []) {
            $mail->markFailed('No recipient(s) were specified.');
            return false;
        }
        if (empty($mail->subject) || empty($mail->body)) {
            $mail->markFailed('Subject or body is empty.');
            return false;
        }

        $message = new EmailMessage(
            $mail->subject,
            $mail->body,
            $recipients,
            $mail->attachments()->toArray(),
        );
        if (!$this->transport->send($message)) {
            $mail->markFailed($this->transport->lastError() ?? 'Unknown transport error.');
            return false;
        }

        $mail->markSent($this->configuration->fromName, $this->configuration->fromEmail);
        return true;
    }

    private function renderMemberTemplate(Member $member, string $text): string
    {
        $values = [
            'VOORNAAM' => (string) $member->voornaam,
            'ACHTERNAAM' => (string) $member->achternaam,
            'IBAN' => (string) $member->iban,
            'AFZENDER' => (string) $this->settings->get('MailFromName'),
        ];

        return str_replace(
            array_map(static fn (string $key): string => '{' . $key . '}', array_keys($values)),
            array_values($values),
            $text,
        );
    }
}
