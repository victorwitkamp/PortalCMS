<?php


declare(strict_types=1);

namespace App\Core\Email\Template;

class EmailTemplate
{
    /**
     * @var int $id
     */
    public int $id;

    /**
     * @var string $type
     */
    public string $type;

    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var string $subject
     */
    public string $subject;

    /**
     * @var string $body
     */
    public string $body;

    /**
     * @var int $status
     */
    public int $status;

    /**
     * @var int $CreatedBy
     */
    public int $CreatedBy;
}
