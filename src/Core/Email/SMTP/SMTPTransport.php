<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\SMTP;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PortalCMS\Core\Email\Message\EmailMessage;

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
     * @var string variable to collect errors
     */
    private $error = '';

    /**
     * @var EmailMessage $emailMessage An e-mail message
     */
    private $emailMessage;

    public function __construct(SMTPConfiguration $config)
    {
        $this->config = $config;
        $this->PHPMailer = new PHPMailer(true);
    }

    /**
     * The different mail sending methods write errors to the error property $this->error,
     * this method simply returns this error / error array.
     */
    public function getError() : string
    {
        return $this->error;
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

    public function verifyMessage(): bool
    {
        if ($this->emailMessage->recipients === null) {
            $this->error = 'Recipients incompleet';
        } else {
            return true;
        }
        return false;
    }

    public function prepareConfiguration(): void
    {
        $this->PHPMailer->CharSet = $this->config->charset;
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true
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

    public function processRecipients(): bool
    {
        if (!empty($this->emailMessage->recipients)) {
            foreach ($this->emailMessage->recipients as $recipient) {
                try {
                    $this->PHPMailer->addAddress($recipient->email, $recipient->name);
                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }
            return true;
        }
        return false;
    }

    public function processAttachments() : bool
    {
        if (!empty($this->emailMessage->attachments)) {
            foreach ($this->emailMessage->attachments as $attachment) {
                $name = $attachment->name . $attachment->extension;
                $fullPath = DIR_ROOT . $attachment->path . $name;
                try {
                    $this->PHPMailer->addAttachment($fullPath, $name, $attachment->encoding, $attachment->type);
                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }
            return true;
        }
        return false;
    }

    public function send(): bool
    {
        try {
            if ($this->PHPMailer->send()) {
                $this->emailMessage = null;
                return true;
            }
        } catch (Exception $e) {
            $this->error = $e->errorMessage();
            return false;
        }
        return false;
    }
}
