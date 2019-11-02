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
     * @var string $name
     */
    public $name;

    /**
     * @var string $subject
     */
    public $subject;

    /**
     * @var string $body
     */
    public $body;

    /**
     * @var string $status
     */
    public $status;

    /**
     * @var int $CreatedBy
     */
    public $CreatedBy;

    public function __construct()
    {
    }
}
