<?php
namespace PortalCMS\Email;

class MailConfiguration
{
    /**
     * @var string
     */
    public $fromEmail;
    /**
     * @var string
     */
    public $fromName;
    /**
     * @var string
     */
    public $SMTPHost;
    /**
     * @var integer
     */
    public $SMTPPort = 25;
    /**
     * @var string
     */
    public $SMTPCrypto = 'tls';

    /**
     * @var boolean
     */
    public $SMTPAuth;
    /**
     * @var string
     */
    public $SMTPUser;
    /**
     * @var string
     */
    public $SMTPPass;


    /**
     * @var string
     */
    // public $mailType = 'text';
    /**
     * Character set (utf-8, iso-8859-1, etc.)
     *
     * @var string
     */
    public $charset = 'UTF-8';

        /**
     * Initialize preferences
     *
     * @param array|\Config\Email $config
     *
     * @return Email
     */
    public function __construct()
    {
        $this->fromEmail = \SiteSetting::getStaticSiteSetting('MailFromEmail');
        $this->fromName = \SiteSetting::getStaticSiteSetting('MailFromName');
        $this->SMTPHost = \SiteSetting::getStaticSiteSetting('MailServer');
        $this->SMTPPort = \SiteSetting::getStaticSiteSetting('MailServerPort');
        $this->SMTPCrypto = \SiteSetting::getStaticSiteSetting('MailServerSecure');
        // $this->SMTPAuth = \SiteSetting::getStaticSiteSetting('MailServerAuth');

        if (\SiteSetting::getStaticSiteSetting('MailServerAuth') === 1) {
            $this->SMTPAuth = true;
        } else {
            $this->SMTPAuth = false;
        }
        $this->SMTPUser = \SiteSetting::getStaticSiteSetting('MailServerUsername');
        $this->SMTPPass = \SiteSetting::getStaticSiteSetting('MailServerPassword');

        if (\SiteSetting::getStaticSiteSetting('MailServerDebug') === 1) {
            $this->SMTPDebug = true;
        } else {
            $this->SMTPDebug = false;
        }


        $this->charset  = strtoupper($this->charset);
        // $this->SMTPAuth = isset($this->SMTPUser[0], $this->SMTPPass[0]);
        return $this;
    }
}