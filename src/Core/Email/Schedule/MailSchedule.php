<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Schedule;

use PHPMailer\PHPMailer\Exception;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Recipient\EmailRecipientCollectionCreator;
use PortalCMS\Core\Email\SMTP\SMTPConfiguration;
use PortalCMS\Core\Email\SMTP\SMTPTransport;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Template\Helpers\MemberTemplateScheduler;
use PortalCMS\Core\Session\Session;

class MailSchedule
{
    public static function deleteById(array $mailIds): bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($mailIds)) {
            Session::add('feedback_negative', 'Invalid request');
        } else {
            foreach ($mailIds as $mailId) {
                if (MailScheduleMapper::deleteById((int) $mailId)) {
                    EmailAttachmentMapper::deleteByMailId((int) $mailId);
                    ++$deleted;
                } else {
                    ++$error;
                }
            }
            if ($deleted > 0) {
                Session::add('feedback_positive', 'Er zijn ' . $deleted . ' berichten verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen mislukt. Aantal berichten met problemen: ' . $error);
        }
        return false;
    }

    public static function isSent(int $mailId): bool
    {
        return MailScheduleMapper::getStatusById($mailId) !== 1;
    }

    public static function sendMailsById(array $mailIds) : bool
    {
        $success = 0;
        $failed = 0;
        $alreadySent = 0;
        if (empty($mailIds)) {
            return false;
        }
        foreach ($mailIds as $mailId) {
            if (self::isSent((int) $mailId)) {
                ++$alreadySent;
            } elseif (self::prepareMailData((int) $mailId)) {
                ++$success;
            } else {
                ++$failed;
            }
        }
        self::sendFeedbackHandler($failed, $success, $alreadySent);
        return true;
    }

    public static function sendFeedbackHandler(int $failed, int $success, int $alreadySent): bool
    {
        if (($success === 0) && ($failed === 0) && ($alreadySent === 0)) {
            Session::add('feedback_negative', 'Invalid request.');
        }
        if ($failed > 0) {
            Session::add('feedback_negative', $failed . ' bericht(en) mislukt.');
        }
        if ($alreadySent > 0) {
            Session::add('feedback_warning', $alreadySent . ' bericht(en) reeds verstuurd.');
        }
        if ($success > 0) {
            Session::add('feedback_positive', $success . ' bericht(en) succesvol verstuurd.');
            return true;
        }
        return false;
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

    public static function sendSingleMailHandler(int $mailId, object $scheduledMail, array $recipients, array $attachments = null) : bool
    {
        $EmailMessage = new EmailMessage(
            $scheduledMail->subject,
            $scheduledMail->body,
            $recipients,
            $attachments
        );
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

    public static function createWithTemplate(int $templateId, array $recipientIds)
    {
        $template = EmailTemplatePDOReader::getById($templateId);
        if (!empty($template)) {
            if ($template->type === 'member') {
                $scheduler = new MemberTemplateScheduler();
                $scheduler->scheduleMails($template, $recipientIds);
            }
        }
    }

    //    public static function create(): bool
    //    {
    //        $create = MailScheduleMapper::create(
    //            null,
    //            Request::post('recipient_email', true),
    //            Request::post('subject', true),
    //            Request::post('body', true)
    //        );
    //        if (!$create) {
    //            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
    //            return false;
    //        }
    //        $created = MailScheduleMapper::lastInsertedId();
    //        Session::add('feedback_positive', 'Email toegevoegd (ID = ' . $created . ')');
    //        return true;
    //    }
}
