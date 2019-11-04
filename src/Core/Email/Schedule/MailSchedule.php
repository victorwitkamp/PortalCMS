<?php
declare(strict_types=1);

namespace PortalCMS\Core\Email\Schedule;

use PortalCMS\Core\Email\Schedule\Helpers\MemberTemplateScheduler;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\SMTP\SMTPTransport;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\SMTP\SMTPConfiguration;
use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientCollectionCreator;

class MailSchedule
{
    public static function deleteById($mailIds): bool
    {
        $deleted = 0;
        $error = 0;
        if (empty($mailIds)) {
            Session::add('feedback_negative', 'Invalid request');
            return false;
        }
        foreach ($mailIds as $mailId) {
            if (!MailScheduleMapper::deleteById($mailId)) {
                ++$error;
            } else {
                EmailAttachmentMapper::deleteByMailId($mailId);
                ++$deleted;
            }
        }
        if ($deleted > 0) {
            Session::add('feedback_positive', 'Er zijn ' . $deleted . ' berichten verwijderd.');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal berichten met problemen: ' . $error);
        return false;
    }

    public static function isSent($mailId): bool
    {
        return MailScheduleMapper::getStatusById($mailId) !== 1;
    }

    public static function sendMailsById($mailIds)
    {
        $success = 0;
        $failed = 0;
        $alreadySent = 0;
        if (empty($mailIds)) {
            return false;
        } else {
            foreach ($mailIds as $mailId) {
                if (!self::isSent((int) $mailId)) {
                    if (self::sendSingleMailHandler((int) $mailId)) {
                        ++$success;
                    } else {
                        ++$failed;
                    }
                } else {
                    ++$alreadySent;
                }
            }
            self::sendFeedbackHandler($failed, $success, $alreadySent);
            return true;
        }
    }

    public static function sendFeedbackHandler($failed, $success, $alreadySent): bool
    {
        if (($success == 0) && ($failed == 0) && ($alreadySent == 0)) {
            Session::add('feedback_negative', 'Invalid request.');
        }
        if ($failed > 0) {
            $failedText = $failed . ' bericht(en) mislukt.';
            Session::add('feedback_negative', $failedText);
        }
        if ($alreadySent > 0) {
            $alreadySentText = $alreadySent . ' bericht(en) reeds verstuurd.';
            Session::add('feedback_warning', $alreadySentText);
        }
        if ($success > 0) {
            $successText = $success . ' bericht(en) succesvol verstuurd.';
            Session::add('feedback_positive', $successText);
            return true;
        }
        return false;
    }

    public static function sendSingleMailHandler($mailId)
    {
        $scheduledMail = MailScheduleMapper::getById($mailId);
        $creator = new EmailRecipientCollectionCreator();
        $recipients = $creator->createCollection($mailId);
        $attachments = EmailAttachmentMapper::getByMailId($mailId);
        if (empty($recipients)) {
            MailScheduleMapper::updateStatus($mailId, 3);
            MailScheduleMapper::setErrorMessageById($mailId, 'No recipient(s) were specified.');
            return false;
        }
        if (empty($scheduledMail['subject']) || empty($scheduledMail['body'])) {
            MailScheduleMapper::updateStatus($mailId, 3);
            MailScheduleMapper::setErrorMessageById($mailId, 'Subject or body is empty.');
            return false;
        }
        $EmailMessage = new EmailMessage(
            $scheduledMail['subject'],
            $scheduledMail['body'],
            $recipients,
            $attachments
        );
        $SMTPConfiguration = new SMTPConfiguration();
        $SMTPTransport = new SMTPTransport($SMTPConfiguration);
        if ($SMTPTransport->sendMail($EmailMessage)) {
            MailScheduleMapper::updateStatus($mailId, 2);
            MailScheduleMapper::updateDateSent($mailId);
            MailScheduleMapper::updateSender($mailId, $SMTPConfiguration->fromName, $SMTPConfiguration->fromEmail);
            return true;
        } else {
            MailScheduleMapper::updateStatus($mailId, 3);
            MailScheduleMapper::setErrorMessageById($mailId, $SMTPTransport->getError());
            return false;
        }
    }

    public static function createWithTemplate($templateId, $recipientIds)
    {
        $templateReader = new EmailTemplatePDOReader();
        $template = $templateReader->getById($templateId);

        if ($template['type'] === 'member') {
            $scheduler = new MemberTemplateScheduler();
            $scheduler->scheduleMails($template, $recipientIds);
        }
    }

    public static function create(): bool
    {
        $create = MailScheduleMapper::create(
            null,
            Request::post('recipient_email', true),
            Request::post('subject', true),
            Request::post('body', true)
        );
        if (!$create) {
            Session::add('feedback_negative', 'Nieuwe email aanmaken mislukt.');
            return false;
        }
        $created = MailScheduleMapper::lastInsertedId();
        Session::add('feedback_positive', 'Email toegevoegd (ID = ' . $created . ')');
        return true;
    }
}
