<?php
declare(strict_types=1);

namespace PortalCMS\Core\Authorization;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\HTTP\Redirect;

class Authorization
{
    /**
     * Check whether the authenticated user has a specific permission
     * @param string $perm_desc
     * @return bool
     */
    public static function verifyPermission(string $perm_desc): ?bool
    {
        $Permissions = PermissionMapper::getPermissionsByUserId((int) Session::get('user_id'));
        if (!empty($Permissions)) {
            foreach ($Permissions as $Permission) {
                if ($Permission->perm_desc === $perm_desc) {
                    return true;
                }
            }
            Redirect::to('includes/permissionError.php');
            die;
        }
    }

    /**
     * Check whether the logged on user has a specific permission
     * @param string $perm_desc
     * @return bool
     */
    public static function hasPermission(string $perm_desc): bool
    {
        $Permissions = PermissionMapper::getPermissionsByUserId((int) Session::get('user_id'));
        if (!empty($Permissions)) {
            foreach ($Permissions as $Permission) {
                if ($Permission->perm_desc === $perm_desc) {
                    return true;
                }
            }
            return false;
        }
    }
}
