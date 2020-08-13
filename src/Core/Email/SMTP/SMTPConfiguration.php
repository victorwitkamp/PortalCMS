<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\SMTP;

use PortalCMS\Core\Config\SiteSetting;

/**
 * Class SMTPConfiguration
 * @package PortalCMS\Core\Email\SMTP
 */
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
     * @var string
     */
    public $charset = 'UTF-8';

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
