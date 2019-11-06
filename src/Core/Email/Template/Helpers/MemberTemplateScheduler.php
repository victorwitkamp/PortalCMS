<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template\Helpers;

use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Members\MemberModel;

class MemberTemplateScheduler
{
    /**
     * @param $template
     * @param $recipientIds
     * @return bool
     */
    public function scheduleMails($template, array $recipientIds) : bool
    {
        $success = 0;
        $failed = 0;

        if (empty($recipientIds) || empty($template)) {
            return false;
        }
        if (!MailBatch::create($template->id)) {
            return false;
        }
        $batchId = MailBatch::lastInsertedId();
        foreach ($recipientIds as $memberId) {
            if ($this->processSingleMail((int) $memberId, $batchId, $template)) {
                ++$success;
            } else {
                ++$failed;
            }
        }
        $this->processFeedback($success, $failed);
        return true;
    }

    public function processFeedback($success, $failed)
    {
        if ($failed === 0) {
            Session::add('feedback_positive', 'Totaal aantal berichten aangemaakt:' . $success);
        } else {
            Session::add('feedback_warning', 'Totaal aantal berichten aangemaakt: ' . $success . '. Berichten met fout: ' . $failed);
        }
    }

    public function processSingleMail(int $memberId, int $batchId, $template)
    {
        $member = MemberModel::getMemberById($memberId);
        $return = MailScheduleMapper::create(
            $batchId,
            $memberId,
            $template->subject,
            PlaceholderHelper::replaceholdersMember(
                $memberId,
                $template->body
            )
        );
        if (!$return) {
            return false;
        }
        $mailId = MailScheduleMapper::lastInsertedId();
        $memberFullname = $member->voornaam . ' ' . $member->achternaam;
        EmailRecipientMapper::createRecipient($mailId, $member->emailadres, $memberFullname);
        $attachments = EmailAttachmentMapper::getByTemplateId($template->id);
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                EmailAttachmentMapper::create($mailId, $attachment->path, $attachment->name, $attachment->extension, $attachment->encoding, $attachment->type);
            }
        }
        return true;
    }


}
