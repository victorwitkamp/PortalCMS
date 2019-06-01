<?php

/**
 * ContractController
 * Controls everything that is event-related
 */
class MailController
{
    public static $error = '';

    public function __construct() {
        if (isset($_POST['testmail'])) {
            MailController::sendMail($_POST['senderemail'], $_POST['recipientemail'], $_POST['subject'], $_POST['body']);
        }
        if (isset($_POST['testeventmail'])) {
            MailController::sendEventMail($_POST['testeventmail_recipientemail']);
        }
        if (isset($_POST['newScheduledMail'])) {
            MailSchedule::new();
        }
        if (isset($_POST['sendScheduledMailById'])) {
            MailSchedule::sendbyid();
        }
        if (isset($_POST['createMailWithTemplate'])) {
            MailSchedule::newWithTemplate();
        }
        if (isset($_POST['deleteScheduledMailById'])) {

        }
    }

    public static function sendMail($fromEmail, $recipientEmail, $mailSubject, $mailBody)
    {
        //  = Config::get('EMAIL_SMTP_USERNAME');
        $fromName = SiteSetting::getStaticSiteSetting('site_name');

        if (!empty($recipientEmail) && !empty($mailSubject) && !empty($mailBody)) {
            $MailSender = new MailSender;
            if (!$MailSender->sendMail($recipientEmail, $fromEmail, $fromName, $mailSubject, $mailBody)) {
                self::$error = $MailSender->error;
                Session::add('feedback_negative', "MailController: Niet verstuurd. Fout: ".self::$error);
                return false;
            } else {
                Session::add('feedback_positive', "MailController: Mail verstuurd naar: ".$recipientEmail);
                return true;
            }
        } else {
            Session::add('feedback_negative', "MailController: Ongeldig verzoek");
            return false;
        }
    }

    public static function sendEventMail($recipientEmail)
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $body = Event::loadStaticComingEvents();
        if (!empty($body)) {
            if (self::sendMail($sender_email, $recipientEmail, 'Komende evenementen', $body)) {
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