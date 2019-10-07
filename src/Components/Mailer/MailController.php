<?php

/**
 * MailController
 * Controls everything mail-related
 */
class MailController extends Controller
{
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
