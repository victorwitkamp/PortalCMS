<?php

namespace PortalCMS\Core\Authorization;

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\HTTP\Redirect;

class Authorization
{
    /**
     * Check whether the authenticated user has a specific permission
     *
     * @param string $perm_desc
     *
     * @return bool
     */
    public static function verifyPermission($perm_desc)
    {
        foreach (PermissionMapper::getPermissionsByUserId(Session::get('user_id')) as $Permission) {
            if ($Permission['perm_desc'] === $perm_desc) {
                return true;
            }
        }
        Redirect::to('includes/permissionError.php');
        die;
    }

    /**
     * Check whether the logged on user has a specific permission
     *
     * @param string $perm_desc
     *
     * @return bool
     */
    public static function hasPermission($perm_desc)
    {
        foreach (PermissionMapper::getPermissionsByUserId(Session::get('user_id')) as $Permission) {
            if ($Permission['perm_desc'] === $perm_desc) {
                return true;
            }
        }
        return false;
    }
}
