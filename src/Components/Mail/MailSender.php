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
    public $config;
    /**
     * @var mixed variable to collect errors
     */
    private $_error;

    private $subject;
    private $body;

    /**
     * Recipients
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Attachment data
     *
     * @var array
     */
    protected $attachments = [];

    public function __construct(
        $subject,
        $body,
        $recipients,
        $attachments = null
    ) {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipients = $recipients;
        $this->attachments = $attachments;
        // $this->_from_email = $from_email;
        // $this->_from_name = $from_name;
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
    public function sendMail($config = null)
    {
        if ($config = null) {
            $config = new \PortalCMS\Email\MailConfiguration;
            $config = $config->initialize();
        }
        if (empty($this->recipients) || empty($this->subject) || empty($this->body)) {
            $this->_error = 'Incompleet';
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = $config->charset;
                $mail->IsSMTP();
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true)
                    );
                $mail->SMTPDebug = $config->SMTPDebug;
                $mail->SMTPAuth = $config->SMTPAuth;

                $mail->SMTPSecure = SiteSetting::getStaticSiteSetting('MailServerSecure');
                $mail->Host = SiteSetting::getStaticSiteSetting('MailServer');
                $mail->Username = SiteSetting::getStaticSiteSetting('MailServerUsername');
                $mail->Password = SiteSetting::getStaticSiteSetting('MailServerPassword');
                $mail->Port = SiteSetting::getStaticSiteSetting('MailServerPort');

                $mail->From = $config->fromEmail;
                $mail->FromName = $config->fromName;

                foreach ($this->recipients as $recipient) {
                    $mail->AddAddress($recipient['email'], $recipient['name']);
                }

                $mail->Subject = $this->subject;
                $mail->Body = $this->body;
                $mail = $this->addAttachments($mail);
                $mail->isHTML(true);
                return $mail->Send();
        } catch (Exception $e) {
            $this->_error = $e->errorMessage();
            return false;
        } catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }


    private function addAttachments($mail)
    {
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $attachmentFullFilePath = DIR_ROOT.$attachment['path'].$attachment['name'].$attachment['extension'];
                $attachmentFullName = $attachment['name'].$attachment['extension'];
                $mail->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
            }
        }
        return $mail;
    }

}
