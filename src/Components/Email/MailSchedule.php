<?php

use PortalCMS\Email\EmailMessage;
use PortalCMS\Email\SMTPConfiguration;

class MailSchedule
{
    public static function deleteById()
    {
        $deleted = 0;
        $error = 0;
        $IDs = Request::post('id');
        if (!empty($IDs)) {
            foreach ($IDs as $id) {
                if (!MailScheduleMapper::deleteById($id)) {
                    $error += 1;
                } else {
                    MailAttachmentMapper::deleteByMailId($id);
                    $deleted += 1;
                }
            }
        }
        if (!$deleted > 0) {
            Session::add('feedback_negative', "Verwijderen mislukt. Aantal berichten met problemen: ".$error);
            return false;
        }
        Session::add('feedback_positive', "Er zijn ".$deleted." berichten verwijderd.");

        Redirect::mail();
    }


    public static function sendbyid($mail_IDs)
    {
        $count_success = 0;
        $count_failed = 0;
        $count_already_sent = 0;
        if (!empty($mail_IDs)) {
            foreach ($mail_IDs as $id) {
                $row = MailScheduleMapper::getById($id);
                if ($row['status'] !== '1') {
                    $count_already_sent += 1;
                } else {
                    $recipients = MailRecipientMapper::getByMailId($id);
                    $title = $row['subject'];
                    $body = $row['body'];
                    $attachments = MailAttachmentMapper::getByMailId($id);

                    if (!empty($recipients) && !empty($title) && !empty($body)) {
                        $EmailMessage = new EmailMessage($title, $body, $recipients, $attachments);
                        $SMTPConfiguration = new SMTPConfiguration();
                        $MailSender = new MailSender($SMTPConfiguration);
                        if ($MailSender->sendMail($EmailMessage)) {
                            MailScheduleMapper::updateStatus($id, '2');
                            MailScheduleMapper::updateDateSent($id);
                            $count_success += 1;
                        } else {
                            MailScheduleMapper::updateStatus($id, '3');
                            MailScheduleMapper::setErrorMessageById($id, $MailSender->getError());
                            $count_failed += 1;
                        }
                    } else {
                        MailScheduleMapper::updateStatus($id, '3');
                        MailScheduleMapper::setErrorMessageById($id, "Mail incompleet");
                        $count_failed += 1;
                    }
                }
            }
        }
        if ($count_failed > 0) {
            if ($count_success > 0) {
                Session::add('feedback_warning', $count_success.' bericht(en) succesvol verstuurd. '.$count_failed.' bericht(en) mislukt.');
            } else {
                Session::add('feedback_negative', $count_failed.' berichte(n) mislukt.');
            }
        } else {
            Session::add('feedback_positive', $count_success.' bericht(en) succesvol verstuurd. ');
        }
        if ($count_already_sent > 0) {
            Session::add('feedback_positive', $count_already_sent.' bericht(en) reeds verstuurd. ');
        }
        Redirect::mail();
    }

    public static function newWithTemplate()
    {
        $type = Request::post('type', true);
        $templateId = Request::post('templateid', true);
        $template = MailTemplateMapper::getTemplateById($templateId);
        $count_created = 0;
        $count_failed = 0;
        if ($type === 'member') {
            if (!empty($_POST['recipients'])) {
                MailBatch::create($templateId);
                $batch_id = MailBatch::lastInsertedId();
                foreach ($_POST['recipients'] as $memberId) {
                    $member = Member::getMemberById($memberId);
                    $body = self::replaceholdersMember($memberId, $template['body']);
                    $return = MailScheduleMapper::create($batch_id, $memberId, $template['subject'], $body);
                    if (!$return) {
                        $count_failed += 1;
                    } else {
                        $count_created += 1;
                        $mailid = MailScheduleMapper::lastInsertedId();
                        $memberFullname = $member['voornaam'].' '.$member['achternaam'];
                        MailRecipientMapper::create($member['emailadres'], $mailid, 1, $memberFullname);

                        $templateAttachments = MailAttachmentMapper::getByTemplateId($templateId);
                        if (!empty($templateAttachments)) {
                            foreach ($templateAttachments as $templateAttachment) {
                                MailAttachmentMapper::create($mailid, $templateAttachment['path'], $templateAttachment['name'], $templateAttachment['extension'], $templateAttachment['encoding'], $templateAttachment['type']);
                            }
                        }
                    }
                }
                if ($count_failed === 0) {
                    Session::add('feedback_positive', "Totaal aantal berichten aangemaakt:".$count_created);
                    Redirect::mail();
                } else {
                    Session::add('feedback_warning', "Totaal aantal berichten aangemaakt: ".$count_created.". Berichten met fout: ".$count_failed);
                    Redirect::mail();
                }
            }
        }
    }

    public static function replaceholdersMember($memberid, $templatebody)
    {
        $member = Member::getMemberById($memberid);
        $afzender = SiteSetting::getStaticSiteSetting('site_name');
        $variables = array(
            "voornaam" => $member['voornaam'],
            "achternaam" => $member['achternaam'],
            "iban" => $member['iban'],
            "afzender" => $afzender
        );
        foreach ($variables as $key => $value) {
            $templatebody = str_replace('{'.strtoupper($key).'}', $value, $templatebody);
        }
        return $templatebody;
    }

    public static function new()
    {
        $recipient_email = Request::post('recipient_email', true);
        $subject = Request::post('subject', true);
        $body = Request::post('body', true);
        $create = MailScheduleMapper::create(null, $recipient_email, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', "Nieuwe email aanmaken mislukt.");
            return false;
        }
        $created = MailScheduleMapper::lastInsertedId();
        Session::add('feedback_positive', "Email toegevoegd (ID = ".$created.')');
        Redirect::invoices();
        return true;
    }

    public static function sendEventMail($recipient)
    {
        $body = Event::loadMailEvents();
        if (!empty($body)) {
            $MailSender = new MailSender('Komende evenementen', $body, $recipient);
            if ($MailSender->sendMail()) {
                return true;
            } else {
                return false;
            }
        } else {
            Session::add('feedback_negative', "MailController: Geen evenementen om te versturen");
            return false;
        }
    }
}
