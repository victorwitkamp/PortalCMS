<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\SMTP;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PortalCMS\Core\Email\Message\EmailMessage;

/**
 * Class Mail
 */
class SMTPTransport
{
    /**
     * @var SMTPConfiguration SMTP configuration object
     */
    private $config;

    /**
     * @var PHPMailer PHPMailer instance
     */
    private $PHPMailer;

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
        $this->PHPMailer = new PHPMailer(true);
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
            $this->PHPMailer->addAddress($recipient->email, $recipient->name);
        }
        $this->PHPMailer->Subject = $verifiedMessage->subject;
        $this->PHPMailer->Body = $verifiedMessage->body;
        if (!empty($verifiedMessage->attachments)) {
            foreach ($verifiedMessage->attachments as $attachment) {
                $attachmentFullFilePath = DIR_ROOT . $attachment['path'] . $attachment['name'] . $attachment['extension'];
                $attachmentFullName = $attachment['name'] . $attachment['extension'];
                $this->PHPMailer->addAttachment($attachmentFullFilePath, $attachmentFullName, $attachment['encoding'], $attachment['type']);
            }
        }
        try {
            return $this->PHPMailer->send();
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
            return false;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
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

    public function prepareConfiguration()
    {
        $this->PHPMailer->CharSet = $this->config->charset;
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $this->PHPMailer->Host = $this->config->SMTPHost;
        $this->PHPMailer->Port = $this->config->SMTPPort;
        $this->PHPMailer->SMTPSecure = $this->config->SMTPCrypto;
        $this->PHPMailer->SMTPAuth = $this->config->SMTPAuth;
        $this->PHPMailer->Username = $this->config->SMTPUser;
        $this->PHPMailer->Password = $this->config->SMTPPass;
        $this->PHPMailer->SMTPDebug = $this->config->SMTPDebug;
        $this->PHPMailer->Debugoutput = static function ($str, $level) {
            file_put_contents(DIR_ROOT . 'phpmailer.log', gmdate('Y-m-d H:i:s') . "\t$level\t$str\n", FILE_APPEND | LOCK_EX);
        };
        $this->PHPMailer->From = $this->config->fromEmail;
        $this->PHPMailer->FromName = $this->config->fromName;
        if ($this->config->isHTML) {
            $this->PHPMailer->isHTML();
        }
    }
}
