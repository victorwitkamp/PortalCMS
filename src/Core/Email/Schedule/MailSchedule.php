<?php

namespace PortalCMS\Core\Email\Schedule;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\MailSender;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Modules\Members\MemberModel;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Email\Template\MailTemplateMapper;
use PortalCMS\Core\Email\Recipient\MailRecipientMapper;
use PortalCMS\Core\Email\Attachment\MailAttachmentMapper;
use PortalCMS\Core\Email\Configuration\SMTPConfiguration;

class MailSchedule
{
    public static function deleteById($mailIds)
    {
        $deleted = 0;
        $error = 0;
        if (!empty($mailIds)) {
            foreach ($mailIds as $mailId) {
                if (!MailScheduleMapper::deleteById($mailId)) {
                    ++$error;
                } else {
                    MailAttachmentMapper::deleteByMailId($mailId);
                    ++$deleted;
                }
            }
        }
        if ($deleted > 0) {
            Session::add('feedback_positive', 'Er zijn ' . $deleted . ' berichten verwijderd.');
            Redirect::mail();
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal berichten met problemen: ' . $error);
        return false;
    }

    public static function isSent($mailId)
    {
        if (MailScheduleMapper::getStatusById($mailId) !== '1') {
            return true;
        }
        return false;
    }

    public static function sendById($mailIds)
    {
        $success = 0;
        $failed = 0;
        $alreadySent = 0;
        if (!empty($mailIds)) {
            foreach ($mailIds as $mailId) {
                if (self::isSent($mailId)) {
                    ++$alreadySent;
                } else {
                    if (self::sendSingleMailHandler($mailId)) {
                        ++$success;
                    } else {
                        ++$failed;
                    }
                }
            }
            self::sendFeedbackHandler($failed, $success, $alreadySent);
        }
    }

    public static function sendFeedbackHandler($failed = 0, $success = 0, $alreadySent = 0)
    {
        if ($success === 0) {
            if ($failed === 0) {
                if ($alreadySent === 0) {
                    Session::add('feedback_warning', 'niets uitgevoerd');
                } else {
                    Session::add('feedback_warning', $alreadySent . ' bericht(en) reeds verstuurd.');
                }
            } else {
                if ($alreadySent === 0) {
                    Session::add('feedback_negative', $failed . ' bericht(en) mislukt.');
                } else {
                    Session::add('feedback_negative', $failed . ' bericht(en) mislukt. ' . $alreadySent . ' bericht(en) reeds verstuurd.');
                }
            }
        } else {
            if ($failed === 0) {
                if ($alreadySent === 0) {
                    Session::add('feedback_positive', $success . ' bericht(en) succesvol verstuurd.');
                } else {
                    Session::add('feedback_warning', $success . ' bericht(en) succesvol verstuurd. ' . $alreadySent . ' bericht(en) reeds verstuurd.');
                }
            } else {
                if ($alreadySent === 0) {
                    Session::add('feedback_warning', $success . ' bericht(en) succesvol verstuurd. ' . $failed . ' bericht(en) mislukt.');
                } else {
                    Session::add('feedback_warning', $success . ' bericht(en) succesvol verstuurd. ' . $failed . ' bericht(en) mislukt. ' . $alreadySent . ' bericht(en) reeds verstuurd.');
                }
            }
        }
    }

    public static function sendSingleMailHandler($mailId)
    {
        $scheduledMail = MailScheduleMapper::getById($mailId);
        $recipients = MailRecipientMapper::getByMailId($mailId);
        $attachments = MailAttachmentMapper::getByMailId($mailId);
        if (!empty($recipients) && !empty($scheduledMail['subject']) && !empty($scheduledMail['body'])) {
            $EmailMessage = new EmailMessage($scheduledMail['subject'], $scheduledMail['body'], $recipients, $attachments);
            $SMTPConfiguration = new SMTPConfiguration();
            $MailSender = new MailSender($SMTPConfiguration);
            if ($MailSender->sendMail($EmailMessage)) {
                MailScheduleMapper::updateStatus($mailId, '2');
                MailScheduleMapper::updateDateSent($mailId);
                MailScheduleMapper::updateSender($mailId, $SMTPConfiguration->fromName, $SMTPConfiguration->fromEmail);
                return true;
            } else {
                MailScheduleMapper::updateStatus($mailId, '3');
                MailScheduleMapper::setErrorMessageById($mailId, $MailSender->getError());
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
        $template = MailTemplateMapper::getTemplateById($templateId);
        $success = 0;
        $failed = 0;
        if ($template['type'] === 'member') {
            if (!empty($recipientIds)) {
                MailBatch::create($templateId);
                $batch_id = MailBatch::lastInsertedId();
                foreach ($recipientIds as $memberId) {
                    $member = MemberModel::getMemberById($memberId);
                    $return = MailScheduleMapper::create($batch_id, $memberId, $template['subject'], self::replaceholdersMember($memberId, $template['body']));
                    if (!$return) {
                        ++$failed;
                    } else {
                        ++$success;
                        $mailid = MailScheduleMapper::lastInsertedId();
                        $memberFullname = $member['voornaam'] . ' ' . $member['achternaam'];
                        MailRecipientMapper::create($member['emailadres'], $mailid, 1, $memberFullname);

                        $templateAttachments = MailAttachmentMapper::getByTemplateId($template['id']);
                        if (!empty($templateAttachments)) {
                            foreach ($templateAttachments as $templateAttachment) {
                                MailAttachmentMapper::create($mailid, $templateAttachment['path'], $templateAttachment['name'], $templateAttachment['extension'], $templateAttachment['encoding'], $templateAttachment['type']);
                            }
                        }
                    }
                }
                if ($failed === 0) {
                    Session::add('feedback_positive', 'Totaal aantal berichten aangemaakt:' . $success);
                } else {
                    Session::add('feedback_warning', 'Totaal aantal berichten aangemaakt: ' . $success . '. Berichten met fout: ' . $failed);
                }
                Redirect::mail();
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
        Redirect::invoices();
        return true;
    }
}
