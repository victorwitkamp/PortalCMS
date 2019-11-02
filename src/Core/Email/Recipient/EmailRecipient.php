<?php

namespace PortalCMS\Core\Email\Recipient;

class EmailRecipient
{
    public $name = null;

    public $email = null;

    public function __contruct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        // return this;
    }

    public function get() {
        return $this;
    }
}
