<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Features\Email\SMTP;

use PortalCMS\Features\Settings\SiteSetting;

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

    public function __construct(SiteSetting $settings)
    {
        $this->fromEmail = (string) $settings->get('MailFromEmail');
        $this->fromName = (string) $settings->get('MailFromName');
        $this->host = (string) ($settings->get('MailServer') ?? 'localhost');
        $this->port = (int) ($settings->get('MailServerPort') ?? 25);
        $secure = (string) $settings->get('MailServerSecure');
        $this->crypto = in_array($secure, [ 'tls', 'ssl' ], true) ? $secure : '';
        $this->authenticate = $settings->get('MailServerAuth') === 'true';
        $this->username = (string) $settings->get('MailServerUsername');
        $this->password = (string) $settings->get('MailServerPassword');
        $this->debug = (int) $settings->get('MailServerDebug');
        $this->html = $settings->get('MailIsHTML') === 'true';
        $this->charset = 'UTF-8';
    }
}
