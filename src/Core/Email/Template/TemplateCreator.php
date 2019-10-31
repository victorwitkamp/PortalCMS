<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Email\Message\EmailMessage;

class TemplateCreator
{
    public function __construct()
    {
    }

    public function create(string $type, EmailMessage $emailMessage, int $status = null, string $strategy = null)
    {
        return new EmailTemplate($type, $emailMessage, $status);
    }
}
