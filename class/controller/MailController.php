<?php

/**
 * MailController
 * Controls everything mail-related
 */
class MailController extends Controller
{
    public static $error = '';

    public function __construct() {
        parent::__construct();

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
            // TODO
        }
    }

    public static function sendMail($sender, $recipient, $subject, $body)
    {
        $senderName = SiteSetting::getStaticSiteSetting('site_name');
        if (empty($recipient) || empty($subject) || empty($body)) {
            Session::add('feedback_negative', "MailController: Ongeldig verzoek");
            return FALSE;
        }

        $MailSender = new MailSender;
        if (!$MailSender->sendMail($recipient, $sender, $senderName, $subject, $body)) {
            self::$error = $MailSender->error;
            Session::add('feedback_negative', "MailController: Niet verstuurd. Fout: ".self::$error);
            return FALSE;
        } else {
            Session::add('feedback_positive', "MailController: Mail verstuurd naar: ".$recipient);
            return TRUE;
        }
    }

    public static function sendEventMail($recipient)
    {
        $sender = Config::get('EMAIL_SMTP_USERNAME');
        $body = Event::loadStaticComingEvents();
        if (!empty($body)) {
            if (self::sendMail($sender, $recipient, 'Komende evenementen', $body)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            Session::add('feedback_negative', "MailController: Geen evenementen om te versturen");
            return FALSE;
        }
    }

}