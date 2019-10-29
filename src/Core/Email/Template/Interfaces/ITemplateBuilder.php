<?php

namespace PortalCMS\Core\Email\Template\Builders;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\MailTemplate;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;
use PortalCMS\Core\Email\Template\MailTemplateMapper;

class ITemplateBuilder
{
    /**
     * Type of the e-mail template.
     * @var type
     */
    public $type = null;
    public $emailMessage = null;
    // public $attachments = [];
    public $status = null;

    public function __construct()
    {

    }

    public function create(string $type, EmailMessage $emailMessage, string $status = null)
    {
        $this->type = $type;
        $this->emailMessage = $emailMessage;
        $this->status = $status;
        return $this;
    }

    public function addAttachment($file, $templateId = null)
    {
        $attachment = new EmailAttachment($file);
        $attachment->store(null, $templateId);
    }

    public function store()
    {
        if (empty($this->type) || empty($this->emailMessage)) {
            return false;
        }
        $mailTemplate = new MailTemplate($this->type, $this->emailMessage, $this->status);
        $return = MailTemplateMapper::create($mailTemplate);
        if (!empty($return)) {
            Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
            return $return;
        }
        Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        return false;
    }

    public function get() {
        return new MailTemplate($this->type, $this->subject, $this->body, $this->attachments);
    }
}
