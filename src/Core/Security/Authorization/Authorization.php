<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PortalCMS\Core\Session\Session;

class Authorization
{
    public static function hasPermission(string $perm_desc): bool
    {
        $Permissions = PermissionMapper::getPermissionsByUserId((int) Session::get('user_id'));
        if (!empty($Permissions)) {
            foreach ($Permissions as $Permission) {
                if ($Permission->perm_desc === $perm_desc) {
                    return true;
                }
            }
        }
        return false;
    }
}
