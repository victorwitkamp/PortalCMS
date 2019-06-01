<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Class Mail
 *
 * Handles everything regarding mail-sending.
 */
class MailSender
{
    /** @var mixed variable to collect errors */
    public $error;

    /**
     * The main mail sending method, this simply calls a certain mail sending method depending on which mail provider
     * you've selected in the application's config.
     *
     * @param $recipient_email string email
     * @param $from_email string sender's email
     * @param $from_name string sender's name
     * @param $subject string subject
     * @param $body string full mail body text
     * @return bool the success status of the according mail sending method
     */
    public function sendMail($recipient_email, $from_email, $from_name, $subject, $body)
    {
        // returns true if successful, false if not
        return $this->sendMailWithPHPMailer(
            $recipient_email, $from_email, $from_name, $subject, $body
        );
    }

    /**
     * The different mail sending methods write errors to the error property $this->error,
     * this method simply returns this error / error array.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Try to send a mail by using PHPMailer.
     * Make sure you have loaded PHPMailer via Composer.
     * Depending on your EMAIL_USE_SMTP setting this will work via SMTP credentials or via native mail()
     *
     * @param $recipient_email
     * @param $from_email
     * @param $from_name
     * @param $subject
     * @param $body
     *
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendMailWithPHPMailer($recipient_email, $from_email, $from_name, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            if (Config::get('EMAIL_USE_SMTP')) {
                $mail->IsSMTP();
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true)
                    );
                $mail->SMTPDebug = Config::get('EMAIL_SMTP_DEBUG');
                $mail->SMTPAuth = Config::get('EMAIL_SMTP_AUTH');
                if (Config::get('EMAIL_SMTP_ENCRYPTION')) {
                    $mail->SMTPSecure = Config::get('EMAIL_SMTP_ENCRYPTION');
                }
                $mail->Host = Config::get('EMAIL_SMTP_HOST');
                $mail->Username = Config::get('EMAIL_SMTP_USERNAME');
                $mail->Password = Config::get('EMAIL_SMTP_PASSWORD');
                $mail->Port = Config::get('EMAIL_SMTP_PORT');
            } else {
                $mail->IsMail();
            }

            $mail->From = $from_email;
            $mail->FromName = $from_name;
            $mail->AddAddress($recipient_email);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->isHTML(true);
            return $mail->Send();
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
            return false;
        }
        // catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
        //     echo $e->getMessage(); //Boring error messages from anything else!
        // }

    }

}
