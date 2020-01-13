<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */
declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use League\Plates\Engine;
use PortalCMS\Core\Session\Session;

class Authorization
{
    /**
     * Check whether the authenticated user has a specific permission
     * @param string $perm_desc
     * @return bool
     */
    public static function verifyPermission(string $perm_desc)
    {
        if (self::hasPermission($perm_desc)) {
            return true;
        } else {
            header('HTTP/1.0 403 Forbidden', true, 403);
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Error/PermissionError');
            exit();
        }
    }

    /**
     * Check whether the logged on user has a specific permission
     * @param string $perm_desc
     * @return bool
     */
    public static function hasPermission(string $perm_desc): bool
    {
        $Permissions = PermissionMapper::getPermissionsByUserId((int)Session::get('user_id'));
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
