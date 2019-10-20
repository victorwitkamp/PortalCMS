<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PortalCMS\Email\SMTPConfiguration;
use PortalCMS\Email\EmailMessage;

/**
 * Class Mail
 */
class MailSender
{
    /**
     * Undocumented variable
     *
     * @var [type] Mail configuration object
     */
    private $config;
    /**
     * @var mixed variable to collect errors
     */
    private $error;

    public function __construct($config)
    {
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
        return $this->error;
    }

    public function verifyMessage($message) {
        if (!$message instanceof EmailMessage) {
            $this->error = 'Bericht is geen instance van EmailMessage';
            return false;
        }
        if (empty($message->recipients)) {
            $this->error = 'Recipients incompleet';
            return false;
        }
        if (empty($message->subject)) {
            $this->error = 'Subject incompleet';
            return false;
        }
        if (empty($message->body)) {
            $this->error = 'Body incompleet';
            return false;
        }
        return $message;
    }

    /**
     * Try to send a mail by using PHPMailer.
     * Make sure you have loaded PHPMailer via Composer.
     *
     * @param $message An e-mail that should be send
     *
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendMail($message)
    {
        if (!$this->config instanceof \PortalCMS\Email\SMTPConfiguration) {
            $this->error = 'Configuratie is geen instance van SMTPConfiguration';
            return false;
        }
        $verifiedMessage = $this->verifyMessage($message);
        if (!$verifiedMessage) {
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
                    'allow_self_signed' => true
                )
            );
            $mailTransport->Host = $this->config->SMTPHost;
            $mailTransport->Port = $this->config->SMTPPort;
            $mailTransport->SMTPSecure = $this->config->SMTPCrypto;
            $mailTransport->SMTPAuth = $this->config->SMTPAuth;
            $mailTransport->Username = $this->config->SMTPUser;
            $mailTransport->Password = $this->config->SMTPPass;
            $mailTransport->SMTPDebug = $this->config->SMTPDebug;
            $mailTransport->Debugoutput = function ($str, $level) {
                file_put_contents(DIR_ROOT.'phpmailer.log', gmdate('Y-m-d H:i:s'). "\t$level\t$str\n", FILE_APPEND | LOCK_EX);
            };
            $mailTransport->From = $this->config->fromEmail;
            $mailTransport->FromName = $this->config->fromName;
            if ($this->config->isHTML) {
                $mailTransport->isHTML(true);
            }
            foreach ($verifiedMessage->recipients as $recipient) {
                $mailTransport->AddAddress($recipient['email'], $recipient['name']);
            }
            $mailTransport->Subject = $verifiedMessage->subject;
            $mailTransport->Body = $verifiedMessage->body;
            if (!empty($verifiedMessage->attachments)) {
                foreach ($verifiedMessage->attachments as $attachment) {
                    $attachmentFullFilePath = DIR_ROOT . $attachment['path'] . $attachment['name'] . $attachment['extension'];
                    $attachmentFullName = $attachment['name'] . $attachment['extension'];
                    $mailTransport->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
                }
            }

            return $mailTransport->Send();
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
            return false;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
