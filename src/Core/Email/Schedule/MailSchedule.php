<?php

namespace PortalCMS\Core\Email\Schedule;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\SMTP\SMTPTransport;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Modules\Members\MemberModel;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\MailTemplateMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\SMTP\SMTPConfiguration;

class MailSchedule
{
    public static function deleteById($mailIds)
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
            Redirect::to('mail');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal berichten met problemen: ' . $error);
        return false;
    }

    public static function isSent($mailId)
    {
        return MailScheduleMapper::getStatusById($mailId) !== '1';
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
                if (!self::isSent($mailId)) {
                    if (self::sendSingleMailHandler($mailId)) {
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

    public static function sendFeedbackHandler($failed, $success, $alreadySent)
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
        $recipients = EmailRecipientMapper::getAll($mailId);
        $attachments = EmailAttachmentMapper::getByMailId($mailId);
        if (!empty($recipients) && !empty($scheduledMail['subject']) && !empty($scheduledMail['body'])) {
            $EmailMessage = new EmailMessage($scheduledMail['subject'], $scheduledMail['body'], $attachments, $recipients);
            $SMTPConfiguration = new SMTPConfiguration();
            $SMTPTransport = new SMTPTransport($SMTPConfiguration);
            if ($SMTPTransport->sendMail($EmailMessage)) {
                MailScheduleMapper::updateStatus($mailId, '2');
                MailScheduleMapper::updateDateSent($mailId);
                MailScheduleMapper::updateSender($mailId, $SMTPConfiguration->fromName, $SMTPConfiguration->fromEmail);
                return true;
            } else {
                MailScheduleMapper::updateStatus($mailId, '3');
                MailScheduleMapper::setErrorMessageById($mailId, $SMTPTransport->getError());
                return false;
            }
        } else {
            MailScheduleMapper::updateStatus($mailId, '3');
            MailScheduleMapper::setErrorMessageById($mailId, 'Mail incompleet');
            return false;
        }
    }

    public static function newWithTemplate($templateId, $recipientIds)
    {
        $template = MailTemplateMapper::getById($templateId);
        $success = 0;
        $failed = 0;
        if ($template['type'] === 'member') {
            if (!empty($recipientIds)) {
                MailBatch::create($templateId);
                foreach ($recipientIds as $memberId) {
                    $member = MemberModel::getMemberById($memberId);
                    $return = MailScheduleMapper::create(MailBatch::lastInsertedId(), $memberId, $template['subject'], self::replaceholdersMember($memberId, $template['body']));
                    if (!$return) {
                        ++$failed;
                    } else {
                        ++$success;
                        $mailid = MailScheduleMapper::lastInsertedId();
                        $memberFullname = $member['voornaam'] . ' ' . $member['achternaam'];
                        EmailRecipientMapper::createRecipient($mailid, $member['emailadres'], $memberFullname);
                        $templateAttachments = EmailAttachmentMapper::getByTemplateId($template['id']);
                        if (!empty($templateAttachments)) {
                            foreach ($templateAttachments as $templateAttachment) {
                                EmailAttachmentMapper::create($mailid, $templateAttachment['path'], $templateAttachment['name'], $templateAttachment['extension'], $templateAttachment['encoding'], $templateAttachment['type']);
                            }
                        }
                    }
                }
                if ($failed === 0) {
                    Session::add('feedback_positive', 'Totaal aantal berichten aangemaakt:' . $success);
                } else {
                    Session::add('feedback_warning', 'Totaal aantal berichten aangemaakt: ' . $success . '. Berichten met fout: ' . $failed);
                }
                Redirect::to('mail');
            }
        }
    }

    public static function replaceholdersMember($memberid, $templatebody)
    {
        $member = MemberModel::getMemberById($memberid);
        $afzender = SiteSetting::getStaticSiteSetting('site_name');
        $variables = [
            'voornaam' => $member['voornaam'],
            'achternaam' => $member['achternaam'],
            'iban' => $member['iban'],
            'afzender' => $afzender
        ];
        foreach ($variables as $key => $value) {
            $templatebody = str_replace('{' . strtoupper($key) . '}', $value, $templatebody);
        }
        return $templatebody;
    }

    public static function new()
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
