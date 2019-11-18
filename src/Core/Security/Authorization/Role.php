<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

class Role
{
    protected $permissions;

    protected function __construct()
    {
        $this->permissions = [];
    }
}
