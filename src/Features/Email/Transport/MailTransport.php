<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Transport;

use PortalCMS\Features\Email\Message\EmailMessage;

interface MailTransport
{
    public function send(EmailMessage $message): bool;

    public function lastError(): ?string;
}
