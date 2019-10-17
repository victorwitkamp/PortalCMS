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
    // private $mail;
    private $config;
    /**
     * @var mixed variable to collect errors
     */
    private $_error;

    public function __construct() {

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
    public function sendMail($mail = null, $config = null)
    {
        if ($config = null) {
            $config = new \PortalCMS\Email\MailConfiguration;
        }
        if (empty($mail->recipients) || empty($mail->subject) || empty($mail->body)) {
            $this->_error = 'Incompleet';
            return false;
        }

        $mailTransport = new PHPMailer(true);
        try {
            $mailTransport->CharSet = $config->charset;
                $mailTransport->IsSMTP();
                $mailTransport->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true)
                    );
                $mailTransport->SMTPDebug = $config->SMTPDebug;
                $mailTransport->SMTPAuth = $config->SMTPAuth;

                $mailTransport->SMTPSecure = SiteSetting::getStaticSiteSetting('MailServerSecure');
                $mailTransport->Host = SiteSetting::getStaticSiteSetting('MailServer');
                $mailTransport->Username = SiteSetting::getStaticSiteSetting('MailServerUsername');
                $mailTransport->Password = SiteSetting::getStaticSiteSetting('MailServerPassword');
                $mailTransport->Port = SiteSetting::getStaticSiteSetting('MailServerPort');

                $mailTransport->From = $config->fromEmail;
                $mailTransport->FromName = $config->fromName;

                foreach ($mail->recipients as $recipient) {
                    $mailTransport->AddAddress($recipient['email'], $recipient['name']);
                }

                $mailTransport->Subject = $mail->subject;
                $mailTransport->Body = $mail->body;
                // $mailTransport = $this->addAttachments($mailTransport);

                        if (!empty($mail->attachments)) {
            foreach ($mail->attachments as $attachment) {
                $attachmentFullFilePath = DIR_ROOT.$attachment['path'].$attachment['name'].$attachment['extension'];
                $attachmentFullName = $attachment['name'].$attachment['extension'];
                $mailTransport->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
            }
        }
        $mailTransport->isHTML(true);
                return $mailTransport->Send();
        } catch (Exception $e) {
            $this->_error = $e->errorMessage();
            return false;
        } catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }


    private function addAttachments($mail)
    {

        return $mail;
    }

}
