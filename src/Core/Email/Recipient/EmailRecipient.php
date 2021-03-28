<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Recipient;

class EmailRecipient
{
    public $name;

    public $email;

    public function __construct(string $name = null, string $email = null)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function get(): EmailRecipient
    {
        return $this;
    }
}
