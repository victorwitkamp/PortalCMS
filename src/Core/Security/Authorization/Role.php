<?php


declare(strict_types=1);

namespace App\Core\Security\Authorization;

class Role
{
    private array $permissions;

    protected function __construct()
    {
        $this->permissions = [];
    }
}
