<?php
/**
 * Class : MailController (MailController.php)
 * Details : MailController.
 */

class MailController
{
    public static $error = '';
    public static function sendMail($fromEmail, $recipientEmail, $mailSubject, $mailBody)
    {
        //  = Config::get('EMAIL_SMTP_USERNAME');
        $fromName = SiteSettings::getStaticSiteSetting('site_name');
        
        if (!empty($recipientEmail) &&!empty($mailSubject) && !empty($mailBody)) {
            $MailSender = new MailSender;
            if (!$MailSender->sendMail($recipientEmail, $fromEmail, $fromName, $mailSubject, $mailBody)) {
                self::$error = $MailSender->error;
                $_SESSION['response'][] = array("status"=>"error","message"=>"MailController: Niet verstuurd. Fout: ".self::$error);
                return false;
            } else {
                $_SESSION['response'][] = array("status"=>"success","message"=>"MailController: Mail verstuurd naar: ".$recipientEmail);
                return true;
            }
        } else {
            $_SESSION['response'][] = array("status"=>"error","message"=>"MailController: Ongeldig verzoek" );
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
            $_SESSION['response'][] = array("status"=>"error","message"=>"Geen evenementen om te versturen" );
            return false;
        }
    }

}