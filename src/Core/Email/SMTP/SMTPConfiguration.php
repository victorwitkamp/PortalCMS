<?php


declare(strict_types=1);

namespace App\Core\Email\SMTP;

use App\Core\Config\SiteSetting;

class SMTPConfiguration
{
    /**
     * @var null|string
     */
    public ?string $SMTPHost = 'localhost';

    /**
     * @var int|null|string
     */
    public null|int|string $SMTPPort = 25;

    /**
     * Encryption type for the SMTP connection (tls, ssl or empty)
     * @var string
     */
    public string $SMTPCrypto;

    /**
     * @var bool
     */
    public bool|int $SMTPAuth = 0;

    /**
     * @var null|string
     */
    public ?string $SMTPUser;

    /**
     * @var null|string
     */
    public ?string $SMTPPass;

    /**
     * @var int|null|string
     */
    public null|int|string $SMTPDebug = 0;

    /**
     * @var null|string
     */
    public ?string $fromEmail;

    /**
     * @var null|string
     */
    public ?string $fromName;

    /**
     * @var bool
     */
    public bool $isHTML = true;

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     * @var string
     */
    public string $charset = 'UTF-8';

    /**
     * Initialize preferences
     */
    public function __construct()
    {
        $this->fromEmail = SiteSetting::get('MailFromEmail');
        $this->fromName = SiteSetting::get('MailFromName');
        $this->SMTPHost = SiteSetting::get('MailServer');
        $this->SMTPPort = SiteSetting::get('MailServerPort');
        if (SiteSetting::get('MailServerSecure') === 'tls') {
            $this->SMTPCrypto = 'tls';
        } elseif (SiteSetting::get('MailServerSecure') === 'ssl') {
            $this->SMTPCrypto = 'ssl';
        } else {
            $this->SMTPCrypto = '';
        }
        if (SiteSetting::get('MailServerAuth') === 'true') {
            $this->SMTPAuth = true;
        } else {
            $this->SMTPAuth = false;
        }
        $this->SMTPUser = SiteSetting::get('MailServerUsername');
        $this->SMTPPass = SiteSetting::get('MailServerPassword');
        $this->SMTPDebug = SiteSetting::get('MailServerDebug');
        if (SiteSetting::get('MailIsHTML') === 'true') {
            $this->isHTML = true;
        } else {
            $this->isHTML = false;
        }
        $this->charset = strtoupper($this->charset);
    }
}
