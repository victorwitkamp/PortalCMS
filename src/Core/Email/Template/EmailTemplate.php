<?php
declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

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
