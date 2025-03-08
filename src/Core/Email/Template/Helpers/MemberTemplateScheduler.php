<?php


declare(strict_types=1);

namespace App\Core\Email\Template\Helpers;

use App\Core\Email\Batch\MailBatch;
use App\Core\Email\Message\Attachment\EmailAttachmentMapper;
use App\Core\Email\Recipient\EmailRecipientMapper;
use App\Core\Email\Schedule\MailScheduleMapper;
use App\Modules\Members\MemberModel;

class MemberTemplateScheduler
{
    public function scheduleMails(object $template, array $memberIds): bool
    {
        $success = 0;
        $failed = 0;

        if (empty($memberIds) || empty($template) || !MailBatch::create($template->id)) {
            return false;
        }
        $batchId = MailBatch::lastInsertedId();
        foreach ($memberIds as $memberId) {
            if ($this->processSingleMail((int)$memberId, $batchId, $template)) {
                ++$success;
            } else {
                ++$failed;
            }
        }
        $this->processFeedback($success, $failed);
        return true;
    }

    public function processSingleMail(int $memberId = null, int $batchId = null, object $template = null): bool
    {
        if ($memberId === null || $batchId === null || empty($template)) {
            return false;
        }
        $member = MemberModel::getMember($memberId);
        $return = MailScheduleMapper::create($batchId, $memberId, $template->subject, PlaceholderHelper::replaceMemberPlaceholders($memberId, $template->body));
        if (!$return) {
            return false;
        }
        $mailId = MailScheduleMapper::lastInsertedId();
        $memberFullname = $member->voornaam . ' ' . $member->achternaam;
        EmailRecipientMapper::createRecipient($mailId, $member->contactDetails->emailadres, $memberFullname);
        $attachments = EmailAttachmentMapper::getByTemplateId($template->id);
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                EmailAttachmentMapper::create($mailId, $attachment->path, $attachment->name, $attachment->extension, $attachment->encoding, $attachment->type);
            }
        }
        return true;
    }

    public function processFeedback(int $success, int $failed)
    {
        if ($failed === 0) {
            $this->addFlash('success','Totaal aantal berichten aangemaakt:' . $success);
        } else {
            $this->addFlash('warning', 'Totaal aantal berichten aangemaakt: ' . $success . '. Berichten met fout: ' . $failed);
        }
    }
}
