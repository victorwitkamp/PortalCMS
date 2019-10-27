<?php
namespace PortalCMS\Core\Email;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PortalCMS\Core\Email\Configuration\SMTPConfiguration;
use PortalCMS\Core\Email\Message\EmailMessage;

/**
 * Class Mail
 */
class MailSender
{
    /**
     * Undocumented variable
     *
     * @var SMTPConfiguration Mail configuration object
     */
    private $config;

    /**
     * Undocumented variable
     *
     * @var PHPMailer PHPMailer instance
     */
    private $SMTPTransport;

    /**
     * @var mixed variable to collect errors
     */
    private $error;

    /**
     * Constructor
     *
     * @param SMTPConfiguration $config
     */
    public function __construct(SMTPConfiguration $config)
    {
        $this->config = $config;
        $this->SMTPTransport = new PHPMailer(true);
    }

    public function prepareConfiguration()
    {
        $this->SMTPTransport->CharSet = $this->config->charset;
        $this->SMTPTransport->isSMTP();
        $this->SMTPTransport->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $this->SMTPTransport->Host = $this->config->SMTPHost;
        $this->SMTPTransport->Port = $this->config->SMTPPort;
        $this->SMTPTransport->SMTPSecure = $this->config->SMTPCrypto;
        $this->SMTPTransport->SMTPAuth = $this->config->SMTPAuth;
        $this->SMTPTransport->Username = $this->config->SMTPUser;
        $this->SMTPTransport->Password = $this->config->SMTPPass;
        $this->SMTPTransport->SMTPDebug = $this->config->SMTPDebug;
        $this->SMTPTransport->Debugoutput = static function ($str, $level) {
            file_put_contents(DIR_ROOT . 'phpmailer.log', gmdate('Y-m-d H:i:s') . "\t$level\t$str\n", FILE_APPEND | LOCK_EX);
        };
        $this->SMTPTransport->From = $this->config->fromEmail;
        $this->SMTPTransport->FromName = $this->config->fromName;
        if ($this->config->isHTML) {
            $this->SMTPTransport->isHTML(true);
        }
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

    public function verifyMessage(EmailMessage $message)
    {
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
     * @param EmailMessage $message An e-mail that should be send
     *
     * @return bool
     * @throws Exception
     */
    public function sendMail(EmailMessage $message)
    {
        $verifiedMessage = $this->verifyMessage($message);
        if (!$verifiedMessage) {
            return false;
        }
        $this->prepareConfiguration();
        foreach ($verifiedMessage->recipients as $recipient) {
            $this->SMTPTransport->addAddress($recipient['email'], $recipient['name']);
        }
        $this->SMTPTransport->Subject = $verifiedMessage->subject;
        $this->SMTPTransport->Body = $verifiedMessage->body;
        if (!empty($verifiedMessage->attachments)) {
            foreach ($verifiedMessage->attachments as $attachment) {
                $attachmentFullFilePath = DIR_ROOT . $attachment['path'] . $attachment['name'] . $attachment['extension'];
                $attachmentFullName = $attachment['name'] . $attachment['extension'];
                $this->SMTPTransport->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
            }
        }
        try {
            return $this->SMTPTransport->send();
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
            return false;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
