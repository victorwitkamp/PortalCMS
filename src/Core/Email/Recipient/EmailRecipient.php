<?php


declare(strict_types=1);

namespace App\Core\Email\Recipient;

class EmailRecipient
{
    public ?string $name;

    public ?string $email;

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
