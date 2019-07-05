<?php

class MailSchedule
{
    public static function getScheduled() {
        return MailScheduleMapper::getScheduled();
    }
    public static function getHistory() {
        return MailScheduleMapper::getHistory();
    }
    public static function exists($id)
    {
        return MailScheduleMapper::exists($id);
    }

    public static function deleteById()
    {
        $deleted = 0;
        $error = 0;
        if (!empty($_POST['id'])) {
            foreach ($_POST['id'] as $id) {
                if (!MailScheduleMapper::deleteById($id)) {
                    $error += 1;
                } else {
                    $deleted += 1;
                }
            }
        }
        if (!$deleted > 0) {
            Session::add('feedback_negative', "Verwijderen mislukt. Aantal berichten met problemen: ".$error);
            return false;
        }
        Session::add('feedback_positive', "Er zijn ".$deleted." berichten verwijderd.");

        // Redirect::mail();
    }


    public static function sendbyid()
    {
        if (!empty($_POST['id'])) {
            foreach ($_POST['id'] as $id) {
                $row = MailScheduleMapper::getById($id);
                if ($row['status'] !== '1') {
                    Session::add('feedback_negative', "Reeds verstuurd");
                    Redirect::mail();
                    return false;
                } else {
                    $sender = $row['sender_email'];
                    $recipient = $row['recipient_email'];
                    $title = $row['subject'];
                    $body = $row['body'];
                    $attachments = MailAttachmentMapper::getByMailId($id);
                    if (!empty($recipient) && !empty($title) && !empty($body)) {
                        if (MailController::sendMail($sender, $recipient, $title, $body, $attachments)) {
                            MailScheduleMapper::updateStatus($id, '2');
                            MailScheduleMapper::updateDateSent($id);
                        } else {
                            MailScheduleMapper::updateStatus($id, '3');
                            MailScheduleMapper::setErrorMessageById($id, MailController::$error);
                        }
                    } else {
                        Session::add('feedback_negative', "Mail incompleet");
                    }
                }
            }
        }
        Redirect::mail();
    }

    public static function newWithTemplate()
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $type = Request::post('type', true);
        $templateId = Request::post('templateid', true);
        $template = MailTemplate::getTemplateById($templateId);
        $count_created = 0;
        $count_failed = 0;
        if ($type === 'member') {
            if (!empty($_POST['recipients'])) {
                foreach ($_POST['recipients'] as $value) {
                    $member = Member::getMemberById($value);
                    $body = self::replaceholdersMember($value, $template['body']);
                    $return = MailScheduleMapper::create($sender_email, $member['emailadres'], $value, $template['subject'], $body);
                    if (!$return) {
                        $count_failed += 1;
                    } else {
                        $count_created += 1;
                    }
                    $templateAttachments = MailAttachmentMapper::getByTemplateId($templateId);
                    $mailid = MailScheduleMapper::lastInsertedId();
                    if (!empty($templateAttachments)) {
                        foreach ($templateAttachments as $templateAttachment) {
                            MailAttachmentMapper::create($mailid, $templateAttachment['path'], $templateAttachment['name'], $templateAttachment['extension'], $templateAttachment['encoding'], $templateAttachment['type']);
                        }
                    }
                }
                if ($count_failed === 0) {
                    Session::add('feedback_positive', "Totaal aantal berichten aangemaakt:".$count_created);
                    Redirect::mail();
                } else {
                    Session::add('feedback_negative', "Nieuwe email aanmaken mislukt.");
                }
            }
        }
    }

    public static function replaceholdersMember($memberid, $templatebody)
    {
        $member = Member::getMemberById($memberid);
        $afzender = SiteSetting::getStaticSiteSetting('site_name');
        $variables = array(
            "voornaam"=>$member['voornaam'],
            "achternaam"=>$member['achternaam'],
            "iban"=>$member['iban'],
            "afzender"=>$afzender
        );
        foreach ($variables as $key => $value) {
            $templatebody = str_replace('{'.strtoupper($key).'}', $value, $templatebody);
        }
        return $templatebody;
    }

    public static function new()
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $recipient_email = Request::post('recipient_email', true);
        $subject = Request::post('subject', true);
        $body = Request::post('body', true);
        $create = MailScheduleMapper::create($sender_email, $recipient_email, $subject, $body);
        if (!$create) {
            Session::add('feedback_negative', "Nieuwe email aanmaken mislukt.");
            return false;
        }
        $created = MailScheduleMapper::lastInsertedId();
        Session::add('feedback_positive', "Email toegevoegd (ID = ".$created.')');
        Redirect::mail();
        return true;
    }

}