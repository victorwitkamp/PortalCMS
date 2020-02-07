<?php
/**
 * Copyright Victor Witkamp (c) 2020.
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
     * @var EmailMessage $emailMessage An e-mail message
     */
    private $emailMessage;

    public function __construct(SMTPConfiguration $config)
    {
        $this->config = $config;
        $this->PHPMailer = new PHPMailer(true);
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

    public function verifyMessage(): bool
    {
        if (empty($this->emailMessage->recipients)) {
            $this->error = 'Recipients incompleet';
            return false;
        }
        if (empty($this->emailMessage->subject)) {
            $this->error = 'Subject incompleet';
            return false;
        }
        if (empty($this->emailMessage->body)) {
            $this->error = 'Body incompleet';
            return false;
        }
        return true;
    }

    public function sendMail(EmailMessage $emailMessage): bool
    {
        $this->emailMessage = $emailMessage;
        if (!$this->verifyMessage()) {
            return false;
        }
        $this->prepareConfiguration();
        $this->processRecipients();
        $this->PHPMailer->Subject = $this->emailMessage->subject;
        $this->PHPMailer->Body = $this->emailMessage->body;
        $this->processAttachments();
        return $this->send();
    }

    public function send() : bool
    {
        try {
            $this->PHPMailer->send();
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
        } finally {
            $this->emailMessage = null;
            return true;
        }
    }

    public function processRecipients() : bool
    {
        if (!empty($this->emailMessage->recipients)) {
            foreach ($this->emailMessage->recipients as $recipient) {
                try {
                    $this->PHPMailer->addAddress($recipient->email, $recipient->name);
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
            return true;
        }
        return false;
    }

    public function processAttachments()
    {
        if (!empty($this->emailMessage->attachments)) {
            foreach ($this->emailMessage->attachments as $attachment) {
                $name = $attachment['name'] . $attachment['extension'];
                $fullPath = DIR_ROOT . $attachment['path'] . $name;
                try {
                    $this->PHPMailer->addAttachment($fullPath, $name, $attachment['encoding'], $attachment['type']);
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
        }
    }
}
