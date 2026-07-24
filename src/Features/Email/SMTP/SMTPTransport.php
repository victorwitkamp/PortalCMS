<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\SMTP;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PortalCMS\Features\Email\Entity\MailAttachment;
use PortalCMS\Features\Email\Message\EmailMessage;
use PortalCMS\Features\Email\Transport\MailTransport;

final class SMTPTransport implements MailTransport
{
    private ?string $error = null;

    public function __construct(private readonly SMTPConfiguration $configuration)
    {
    }

    public function send(EmailMessage $message): bool
    {
        $this->error = null;
        if ($message->recipients === [] || $message->subject === '' || $message->body === '') {
            $this->error = 'Recipients, subject, or body is incomplete.';
            return false;
        }

        $mailer = $this->createMailer();

        try {
            foreach ($message->recipients as $recipient) {
                $mailer->addAddress($recipient->email, $recipient->name ?? '');
            }
            foreach ($message->attachments as $attachment) {
                $this->addAttachment($mailer, $attachment);
            }

            $mailer->Subject = $message->subject;
            $mailer->Body = $message->body;
            $mailer->send();

            return true;
        } catch (Exception $exception) {
            $this->error = $exception->getMessage();
            return false;
        }
    }

    public function lastError(): ?string
    {
        return $this->error;
    }

    private function createMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);
        $mailer->CharSet = $this->configuration->charset;
        $mailer->isSMTP();
        $mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        $mailer->Host = $this->configuration->host;
        $mailer->Port = $this->configuration->port;
        $mailer->SMTPSecure = $this->configuration->crypto;
        $mailer->SMTPAuth = $this->configuration->authenticate;
        $mailer->Username = $this->configuration->username;
        $mailer->Password = $this->configuration->password;
        $mailer->SMTPDebug = $this->configuration->debug;
        $mailer->Debugoutput = static function (string $message, int $level): void {
            file_put_contents(
                DIR_ROOT . 'phpmailer.log',
                gmdate('Y-m-d H:i:s') . "\t$level\t$message\n",
                FILE_APPEND | LOCK_EX,
            );
        };
        $mailer->setFrom($this->configuration->fromEmail, $this->configuration->fromName);
        if ($this->configuration->html) {
            $mailer->isHTML();
        }

        return $mailer;
    }

    private function addAttachment(PHPMailer $mailer, MailAttachment $attachment): void
    {
        $extension = (string) $attachment->extension;
        if ($extension !== '' && !str_starts_with($extension, '.')) {
            $extension = '.' . $extension;
        }
        $fileName = (string) $attachment->name . $extension;
        $mailer->addAttachment(
            DIR_ROOT . (string) $attachment->path . $fileName,
            $fileName,
            $attachment->encoding ?? 'base64',
            $attachment->type ?? 'application/octet-stream',
        );
    }
}
