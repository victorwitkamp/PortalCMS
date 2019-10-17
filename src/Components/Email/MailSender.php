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
    private $mail;
    private $config;
    /**
     * @var mixed variable to collect errors
     */
    private $_error;

    public function __construct($mail, $config)
    {
        $this->mail = $mail;
        $this->config = $config;
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
        if (empty($this->mail->recipients)) {
            $this->_error = 'Recipients incompleet';
            // var_dump($this->mail->recipients);
            // die;
            return false;
        }
        if (empty($this->mail->subject)) {
            $this->_error = 'Subject incompleet';
            // var_dump($this->mail->subject);
            // die;
            return false;
        }
        if (empty($this->mail->body)) {
            $this->_error = 'Body incompleet';
            // var_dump($this->mail->body);
            // die;
            return false;
        }

        $mailTransport = new PHPMailer(true);
        try {
            $mailTransport->CharSet = $this->config->charset;
                $mailTransport->IsSMTP();
                $mailTransport->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true)
                    );
                $mailTransport->SMTPDebug = $this->config->SMTPDebug;
                $mailTransport->SMTPAuth = $this->config->SMTPAuth;

                $mailTransport->SMTPSecure = SiteSetting::getStaticSiteSetting('MailServerSecure');
                $mailTransport->Host = SiteSetting::getStaticSiteSetting('MailServer');
                $mailTransport->Username = SiteSetting::getStaticSiteSetting('MailServerUsername');
                $mailTransport->Password = SiteSetting::getStaticSiteSetting('MailServerPassword');
                $mailTransport->Port = SiteSetting::getStaticSiteSetting('MailServerPort');

                $mailTransport->From = $this->config->fromEmail;
                $mailTransport->FromName = $this->config->fromName;

                foreach ($this->mail->recipients as $recipient) {
                    $mailTransport->AddAddress($recipient['email'], $recipient['name']);
                }

                $mailTransport->Subject = $this->mail->subject;
                $mailTransport->Body = $this->mail->body;
                // $mailTransport = $this->addAttachments($mailTransport);

                        if (!empty($this->mail->attachments)) {
            foreach ($this->mail->attachments as $attachment) {
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



}
