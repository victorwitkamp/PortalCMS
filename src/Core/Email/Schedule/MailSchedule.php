<?php


declare(strict_types=1);

namespace App\Core\Email\Schedule;

use App\Core\Email\Message\Attachment\EmailAttachmentMapper;
use App\Core\Email\Message\EmailMessage;
use App\Core\Email\Recipient\EmailRecipientCollectionCreator;
use App\Core\Email\SMTP\SMTPConfiguration;
use App\Core\Email\SMTP\SMTPTransport;
use App\Core\Email\Template\EmailTemplateMapper;
use App\Core\Email\Template\Helpers\MemberTemplateScheduler;

class MailSchedule
{
    public function deleteById(array $mailIds): bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($mailIds)) {
            $this->addFlash('danger','Invalid request');
        } else {
            foreach ($mailIds as $mailId) {
                if (MailScheduleMapper::deleteById((int)$mailId)) {
                    EmailAttachmentMapper::deleteByMailId((int)$mailId);
                    ++$deleted;
                } else {
                    ++$error;
                }
            }
            if ($deleted > 0) {
                $this->addFlash('success','Er zijn ' . $deleted . ' berichten verwijderd.');
                return true;
            }
            $this->addFlash('danger','Verwijderen mislukt. Aantal berichten met problemen: ' . $error);
        }
        return false;
    }

    public function sendMailsById(array $mailIds): bool
    {
        $success = 0;
        $failed = 0;
        $alreadySent = 0;
        if (empty($mailIds)) {
            return false;
        }
        foreach ($mailIds as $mailId) {
            if (self::isSent((int)$mailId)) {
                ++$alreadySent;
            } elseif (self::prepareMailData((int)$mailId)) {
                ++$success;
            } else {
                ++$failed;
            }
        }
        self::sendFeedbackHandler($failed, $success, $alreadySent);
        return true;
    }

    public static function isSent(int $mailId): bool
    {
        return MailScheduleMapper::getStatusById($mailId) !== 1;
    }

    public static function prepareMailData(int $mailId): bool
    {
        $scheduledMail = MailScheduleMapper::getById($mailId);
        if (!empty($scheduledMail)) {
            $creator = new EmailRecipientCollectionCreator();
            $recipients = $creator->createCollection($mailId);
            $attachments = EmailAttachmentMapper::getByMailId($mailId);
            if (empty($recipients)) {
                MailScheduleMapper::updateStatus($mailId, 3);
                MailScheduleMapper::setErrorMessageById($mailId, 'No recipient(s) were specified.');
            } elseif (empty($scheduledMail->subject) || empty($scheduledMail->body)) {
                MailScheduleMapper::updateStatus($mailId, 3);
                MailScheduleMapper::setErrorMessageById($mailId, 'Subject or body is empty.');
            } else {
                return self::sendSingleMailHandler($mailId, $scheduledMail, $recipients, $attachments);
            }
        }
        return false;
    }

    public static function sendSingleMailHandler(int $mailId, object $scheduledMail, array $recipients, array $attachments = null): bool
    {
        $EmailMessage = new EmailMessage($scheduledMail->subject, $scheduledMail->body, $recipients, $attachments);
        $configuration = new SMTPConfiguration();
        $transport = new SMTPTransport($configuration);
        if (!$transport->sendMail($EmailMessage)) {
            MailScheduleMapper::updateStatus($mailId, 3);
            MailScheduleMapper::setErrorMessageById($mailId, $transport->getError());
            return false;
        }
        MailScheduleMapper::updateStatus($mailId, 2);
        MailScheduleMapper::updateDateSent($mailId);
        MailScheduleMapper::updateSender($mailId, $configuration->fromName, $configuration->fromEmail);
        return true;
    }

    public function sendFeedbackHandler(int $failed, int $success, int $alreadySent): bool
    {
        if (($success === 0) && ($failed === 0) && ($alreadySent === 0)) {
            $this->addFlash('danger','Invalid request.');
        }
        if ($failed > 0) {
            $this->addFlash('danger',$failed . ' bericht(en) mislukt.');
        }
        if ($alreadySent > 0) {
            $this->addFlash('warning', $alreadySent . ' bericht(en) reeds verstuurd.');
        }
        if ($success > 0) {
            $this->addFlash('success',$success . ' bericht(en) succesvol verstuurd.');
            return true;
        }
        return false;
    }

    public static function createWithTemplate(int $templateId, array $recipientIds)
    {
        $template = EmailTemplateMapper::getById($templateId);
        if (!empty($template) && $template->type === 'member') {
            $scheduler = new MemberTemplateScheduler();
            $scheduler->scheduleMails($template, $recipientIds);
        }
    }

    //    public static function create(): bool
    //    {
    //        $create = MailScheduleMapper::create(
    //            null,
    //            $this->request->get('recipient_email'),
    //            $this->request->get('subject'),
    //            $this->request->get('body')
    //        );
    //        if (!$create) {
    //            $this->addFlash('danger','Nieuwe email aanmaken mislukt.');
    //            return false;
    //        }
    //        $created = MailScheduleMapper::lastInsertedId();
    //        $this->addFlash('success','Email toegevoegd (ID = ' . $created . ')');
    //        return true;
    //    }
}
