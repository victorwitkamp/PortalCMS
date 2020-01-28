<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\SMTP;

use PortalCMS\Core\Config\SiteSetting;

class SMTPConfiguration
{
    /**
     * @var string
     */
    public $SMTPHost = 'localhost';

    /**
     * @var int
     */
    public $SMTPPort = 25;

    /**
     * Encryption type for the SMTP connection (tls, ssl or empty)
     *
     * @var string
     */
    public $SMTPCrypto;

    /**
     * @var bool
     */
    public $SMTPAuth = 0;

    /**
     * @var string
     */
    public $SMTPUser;

    /**
     * @var string
     */
    public $SMTPPass;

    /**
     * @var int
     */
    public $SMTPDebug = 0;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var bool
     */
    public $isHTML = true;

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     *
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * Initialize preferences
     *
     */
    public function __construct()
    {
        $this->fromEmail = SiteSetting::getStaticSiteSetting('MailFromEmail');
        $this->fromName = SiteSetting::getStaticSiteSetting('MailFromName');
        $this->SMTPHost = SiteSetting::getStaticSiteSetting('MailServer');
        $this->SMTPPort = SiteSetting::getStaticSiteSetting('MailServerPort');
        if (SiteSetting::getStaticSiteSetting('MailServerSecure') === 'tls') {
            $this->SMTPCrypto = 'tls';
        } elseif (SiteSetting::getStaticSiteSetting('MailServerSecure') === 'ssl') {
            $this->SMTPCrypto = 'ssl';
        } else {
            $this->SMTPCrypto = '';
        }
        if (SiteSetting::getStaticSiteSetting('MailServerAuth') === 'true') {
            $this->SMTPAuth = true;
        } else {
            $this->SMTPAuth = false;
        }
        $this->SMTPUser = SiteSetting::getStaticSiteSetting('MailServerUsername');
        $this->SMTPPass = SiteSetting::getStaticSiteSetting('MailServerPassword');
        $this->SMTPDebug = SiteSetting::getStaticSiteSetting('MailServerDebug');
        if (SiteSetting::getStaticSiteSetting('MailIsHTML') === 'true') {
            $this->isHTML = true;
        } else {
            $this->isHTML = false;
        }
        $this->charset = strtoupper($this->charset);
    }
}
