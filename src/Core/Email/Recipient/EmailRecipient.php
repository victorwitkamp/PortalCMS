<?php
declare(strict_types=1);

namespace PortalCMS\Core\Email\Recipient;

class EmailRecipient
{
    public $name;

    public $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function get(): EmailRecipient
    {
        return $this;
    }
}
