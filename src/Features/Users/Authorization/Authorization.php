<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Authorization;

use PortalCMS\Features\Users\Authentication\Authentication;
use PortalCMS\Features\Users\Entity\Permission;
use PortalCMS\Features\Users\Repository\PermissionRepository;

final class Authorization
{
    public function __construct(
        private readonly PermissionRepository $permissions,
        private readonly Authentication $authentication,
    ) {
    }

    public function hasPermission(string $description): bool
    {
        foreach ($this->permissions->findByUserId($this->authentication->userId()) as $permission) {
            if ($permission instanceof Permission && $permission->perm_desc === $description) {
                return true;
            }
        }

        return false;
    }
}
