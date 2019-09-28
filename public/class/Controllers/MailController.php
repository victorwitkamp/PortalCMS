<?php

/**
 * MailController
 * Controls everything mail-related
 */
class MailController extends Controller
{
    public static $error = '';

    public function __construct()
    {
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
            MailSchedule::deleteById();
        }
    }

    public static function sendMail($sender, $recipients, $subject, $body, $attachments, $cc_recipient = NULL)
    {
        $senderName = SiteSetting::getStaticSiteSetting('site_name');
        if (empty($recipients) || empty($subject) || empty($body)) {
            Session::add('feedback_negative', "MailController: Ongeldig verzoek");
            return false;
        }

        $MailSender = new MailSender;
        if ($MailSender->sendMail($recipients, $sender, $senderName, $subject, $body, $attachments, $cc_recipient)) {
            // Session::add('feedback_positive', "MailController: Mail verstuurd naar: ".$recipient);
            // return $recipient;
            return true;
        }
        self::$error = $MailSender->error;
        // Session::add('feedback_negative', "MailController: Niet verstuurd. Fout: ".self::$error);
        return false;
    }

    public static function sendEventMail($recipient)
    {
        $sender = Config::get('EMAIL_SMTP_USERNAME');
        $body = Event::loadMailEvents();
        if (!empty($body)) {
            if (self::sendMail($sender, $recipient, 'Komende evenementen', $body)) {
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
