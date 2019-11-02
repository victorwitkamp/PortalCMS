<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\EmailTemplate;

class EmailTemplateBuilder
{
    /**
     * Instance of the MailTemplateMapper class
     *
     * @var EmailTemplate $emailTemplate
     */
    public $emailTemplate;

    /**
     * Instance of the MailTemplateMapper class
     *
     * @var EmailTemplatePDOWriter $EmailTemplatePDOWriter
     */
    public $EmailTemplatePDOWriter;

    public function __construct()
    {
        $this->EmailTemplatePDOWriter = new EmailTemplatePDOWriter();
    }

    public function create(string $type, EmailMessage $emailMessage)
    {
        $this->emailTemplate = new EmailTemplate();
        $this->emailTemplate->type = $type;
        $this->emailTemplate->subject = $emailMessage->subject;
        $this->emailTemplate->body = $emailMessage->body;
    }

    /**
     * Save the EmailTemplate to the database and return the id of the created record.
     *
     * @param EmailTemplate $emailTemplate
     *
     * @return void
     */
    public function store(EmailTemplate $emailTemplate)
    {
        return $this->EmailTemplatePDOWriter->store($emailTemplate);
    }
}
