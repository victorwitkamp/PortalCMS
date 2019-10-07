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
    /**
     * @var mixed variable to collect errors
     */
    private $_error;
    private $_subject;
    private $_body;
    private $_recipients;
    private $_attachments;
    private $_from_email;
    private $_from_name;

    public function __construct(
        $subject,
        $body,
        $recipients,
        $attachments = NULL,
        $from_email = NULL,
        $from_name = NULL
    ) {
        $this->_subject = $subject;
        $this->_body = $body;
        $this->_recipients = $recipients;
        $this->_attachments = $attachments;
        $this->_from_email = $from_email;
        $this->_from_name = $from_name;
    }

    /**
     * The different mail sending methods write errors to the error property $this->error,
     * this method simply returns this error / error array.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->_error;
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
    public function sendMail()
    {
        if (empty($this->_recipients) || empty($this->_subject) || empty($this->_body)) {
            $this->error = 'Incompleet';
            return false;
        }
        if ($this->_from_email == NULL) {
            $this->_from_email = Config::get('EMAIL_SMTP_USERNAME');
        }
        if ($this->_from_name == NULL) {
                $this->_from_name = SiteSetting::getStaticSiteSetting('site_name');
        }

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

            $mail->From = $this->_from_email;
            $mail->FromName = $this->_from_name;
            foreach ($this->_recipients as $recipient) {
                $mail->AddAddress($recipient['email'], $recipient['name']);
            }
            // $mail->AddAddress($recipient);
            // if (!empty($cc_recipient)) {
            //     $mail->AddCC($cc_recipient);
            // }
            $mail->Subject = $this->_subject;
            $mail->Body = $this->_body;
            if (!empty($this->_attachments)) {
                foreach ($this->_attachments as $attachment) {
                    $attachmentFullFilePath = DIR_ROOT.$attachment['path'].$attachment['name'].$attachment['extension'];
                    $attachmentFullName = $attachment['name'].$attachment['extension'];
                    $mail->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
                }
            }
            $mail->isHTML(true);
            return $mail->Send();
        } catch (Exception $e) {
            $this->_error = $e->errorMessage();
            return false;
        } catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
            echo $e->getMessage(); //Boring error messages from anything else!
        }

    }

}
