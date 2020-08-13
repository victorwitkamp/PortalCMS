<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

/**
 * Class Role
 * @package PortalCMS\Core\Security\Authorization
 */
class Role
{
    protected $permissions;

    protected function __construct()
    {
        $this->permissions = [];
    }
}
