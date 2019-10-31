<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachment;

class EmailTemplate
{
    /**
     * Type of the e-mail template.
     * @var int $id
     */
    public $id = null;
    /**
     * Type of the e-mail template.
     * @var string $type
     */
    public $type = null;
    public $emailMessage = null;
    public $status = null;

    public function __construct()
    {

    }

    public function set(string $type, EmailMessage $emailMessage, int $status = null) {
        $this->type = $type;
        $this->emailMessage = $emailMessage;
        $this->status = $status;
        // return $this;
    }

    public function get(MailTemplateMapper $mapper, $id)
    {
        $template = $mapper->getById($id);
        $this->type = $template->type;
        $this->emailMessage = new EmailMessage($template->subject, $template->body);
        $this->status = $template->status;
        return $this;
    }

    public function store(MailTemplateMapper $mapper)
    {
        if (empty($this->type)) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt. Type is leeg.');
            return false;
        }
        if (empty($this->emailMessage)) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt. emailMessage is leeg.');
            return false;
        }
        $return = $mapper->create($this);
        if (!empty($return)) {
            Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
            return $return;
        }
        Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        return false;
    }
}
