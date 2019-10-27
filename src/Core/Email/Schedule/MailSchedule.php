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
use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Core\Email\Template\MailTemplateMapper;
use PortalCMS\Core\Email\Recipient\MailRecipientMapper;
use PortalCMS\Core\Email\Attachment\MailAttachmentMapper;
use PortalCMS\Core\Email\Configuration\SMTPConfiguration;

class MailSchedule
{
    public static function deleteById($IDs)
    {
        $deleted = 0;
        $error = 0;
        if (!empty($IDs)) {
            foreach ($IDs as $id) {
                if (!MailScheduleMapper::deleteById($id)) {
                    ++$error;
                } else {
                    MailAttachmentMapper::deleteByMailId($id);
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


    public static function sendbyid($mail_IDs)
    {
        $success = 0;
        $failed = 0;
        $alreadySent = 0;
        if (!empty($mail_IDs)) {
            foreach ($mail_IDs as $id) {
                $row = MailScheduleMapper::getById($id);
                if ($row['status'] !== '1') {
                    ++$alreadySent;
                } else {
                    if (self::sendSingleMailHandler($id, $row)) {
                        ++$success;
                    } else {
                        ++$failed;
                    }
                }
            }
        }
        self::handleFeedback($failed, $success, $alreadySent);
        Redirect::mail();
    }

    public static function handleFeedback($failed, $success, $alreadySent) {
        if ($failed > 0) {
            if ($success > 0) {
                Session::add('feedback_warning', $success . ' bericht(en) succesvol verstuurd. ' . $failed . ' bericht(en) mislukt.');
            } else {
                Session::add('feedback_negative', $failed . ' berichte(n) mislukt.');
            }
        } else {
            Session::add('feedback_positive', $success . ' bericht(en) succesvol verstuurd. ');
        }
        if ($alreadySent > 0) {
            Session::add('feedback_positive', $alreadySent . ' bericht(en) reeds verstuurd. ');
        }
    }

    public static function sendSingleMailHandler($id, $row)
    {
        $recipients = MailRecipientMapper::getByMailId($id);
        $attachments = MailAttachmentMapper::getByMailId($id);
        if (!empty($recipients) && !empty($row['subject']) && !empty($row['body'])) {
            $EmailMessage = new EmailMessage($row['subject'], $row['body'], $recipients, $attachments);
            $SMTPConfiguration = new SMTPConfiguration();
            $MailSender = new MailSender($SMTPConfiguration);
            if ($MailSender->sendMail($EmailMessage)) {
                MailScheduleMapper::updateStatus($id, '2');
                MailScheduleMapper::updateDateSent($id);
                return true;
            } else {
                MailScheduleMapper::updateStatus($id, '3');
                MailScheduleMapper::setErrorMessageById($id, $MailSender->getError());
                return false;
            }
        } else {
            MailScheduleMapper::updateStatus($id, '3');
            MailScheduleMapper::setErrorMessageById($id, 'Mail incompleet');
            return false;
        }
    }

    public static function newWithTemplate()
    {
        $type = Request::post('type', true);
        $templateId = Request::post('templateid', true);
        $template = MailTemplateMapper::getTemplateById($templateId);
        $success = 0;
        $failed = 0;
        if ($type === 'member') {
            if (!empty($_POST['recipients'])) {
                MailBatch::create($templateId);
                $batch_id = MailBatch::lastInsertedId();
                foreach ($_POST['recipients'] as $memberId) {
                    $member = MemberModel::getMemberById($memberId);
                    $body = self::replaceholdersMember($memberId, $template['body']);
                    $return = MailScheduleMapper::create($batch_id, $memberId, $template['subject'], $body);
                    if (!$return) {
                        ++$failed;
                    } else {
                        ++$success;
                        $mailid = MailScheduleMapper::lastInsertedId();
                        $memberFullname = $member['voornaam'] . ' ' . $member['achternaam'];
                        MailRecipientMapper::create($member['emailadres'], $mailid, 1, $memberFullname);

                        $templateAttachments = MailAttachmentMapper::getByTemplateId($templateId);
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

    public static function sendEventMail($recipient)
    {
        $body = CalendarEventModel::loadMailEvents();
        if (!empty($body)) {
            $MailSender = new MailSender(new SMTPConfiguration());
            if ($MailSender->sendMail(new EmailMessage(
                'Komende evenementen',
                $body,
                $recipient
            ))) {
                return true;
            } else {
                return false;
            }
        } else {
            Session::add('feedback_negative', 'MailController: Geen evenementen om te versturen');
            return false;
        }
    }
}
