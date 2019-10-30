<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Email\Message\EmailMessage;
use PortalCMS\Core\Email\Template\EmailTemplate;

class TemplateCreator
{
    /**
     * Template object.
     * @var EmailTemplate $template
     */
    public $type = null;
    public $emailMessage = null;
    public $status = null;
    public $EmailTemplate = null;

    public function __construct(string $type, EmailMessage $emailMessage, string $status = null, string $strategy = null)
    {
        echo '<strong>TemplateCreator construct</strong>';
        echo '<br>';
        print_r($type);
        echo '<br>';
        print_r($emailMessage);
        echo '<br>';
        print_r($status);
        echo '<br>';
        $this->type = $type;
        $this->emailMessage = $emailMessage;
        $this->status = $status;
        echo '<br>';

        print_r($this->type);
        print_r($this->emailMessage);
        print_r($this->status);
        echo '<br><strong>new EmailTemplate</strong><br>';
        $EmailTemplate = new EmailTemplate($type, $emailMessage, $status);
        echo '<strong>TemplateCreator construct continue...</strong><br>';
        print_r($EmailTemplate);
        echo '<hr>';
    }

    public function create(string $type, EmailMessage $emailMessage, string $status = null)
    {
        $EmailTemplate = new EmailTemplate($type, $emailMessage, $status);
        return $EmailTemplate;
    }
}
