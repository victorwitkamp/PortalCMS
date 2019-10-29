<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Email\Message\EmailMessage;

class MailTemplate
{
    /**
     * Type of the e-mail template.
     * @var type
     */
    private $type = null;

    private $emailMessage = null;

    private $status = null;

    public function __contruct(string $type, EmailMessage $emailMessage, string $status) : object
    {
        $this->type = $type;
        $this->emailMessage = $emailMessage;
        $this->status = $status;
        return $this;
    }
}
