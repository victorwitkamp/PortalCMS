<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\Message\EmailMessage;

class EmailTemplate
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var EmailMessage $emailMessage
     */
    public $emailMessage;

    /**
     * @var string = null
     */
    public $status;

    public function __construct()
    {
    }

    public function set(string $type, EmailMessage $emailMessage, int $status = null, $id = null)
    {
        $this->type = $type;
        $this->emailMessage = $emailMessage;
        $this->status = $status;
    }

    public function setById($id)
    {
        $template = $this->EmailTemplatePDOWriter->getById($id);
        $this->id = $template->id;
        $this->type = $template->type;
        $this->emailMessage = new EmailMessage($template->subject, $template->body, null, null);
        $this->status = $template->status;
    }

    public function store(MailTemplateMapper $mapper)
    {
        $this->EmailTemplatePDOWriter;
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
