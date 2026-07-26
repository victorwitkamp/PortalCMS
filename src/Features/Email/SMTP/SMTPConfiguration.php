<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Features\Email\SMTP;

use PortalCMS\Features\Settings\Application\Settings;

class SMTPConfiguration
{
    public readonly string $host;
    public readonly int $port;
    public readonly string $crypto;
    public readonly bool $authenticate;
    public readonly string $username;
    public readonly string $password;
    public readonly int $debug;
    public readonly string $fromEmail;
    public readonly string $fromName;
    public readonly bool $html;
    public readonly string $charset;

    public function __construct(Settings $settings)
    {
        $this->fromEmail = (string) $settings->value('MailFromEmail');
        $this->fromName = (string) $settings->value('MailFromName');
        $this->host = (string) ($settings->value('MailServer') ?? 'localhost');
        $this->port = (int) ($settings->value('MailServerPort') ?? 25);
        $secure = (string) $settings->value('MailServerSecure');
        $this->crypto = in_array($secure, [ 'tls', 'ssl' ], true) ? $secure : '';
        $this->authenticate = $settings->value('MailServerAuth') === 'true';
        $this->username = (string) $settings->value('MailServerUsername');
        $this->password = (string) $settings->value('MailServerPassword');
        $this->debug = (int) $settings->value('MailServerDebug');
        $this->html = $settings->value('MailIsHTML') === 'true';
        $this->charset = 'UTF-8';
    }
}
