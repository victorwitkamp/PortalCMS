<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Session;

/**
 * Class RolePermission
 * @package PortalCMS\Core\Security\Authorization
 */
class RolePermission
{

    /**
     */
    public static function assignPermission(int $roleId, int $permId)
    {
        $Permission = PermissionMapper::getById($permId);
        if (RolePermissionMapper::isAssigned($roleId, $Permission['perm_desc'])) {
            Session::add('feedback_negative', 'Reeds toegewezen.');
            Redirect::to('Error/Error');
        } elseif (RolePermissionMapper::assign($roleId, $permId)) {
            Session::add('feedback_positive', 'Permissie toegewezen.');
            Redirect::to('UserManagement/Role/?id=' . $roleId);
        } else {
            Session::add('feedback_negative', 'Fout bij het toewijzen van de permissie.');
            Redirect::to('Error/Error');
        }
    }

    /**
     */
    public static function unassignPermission(int $roleId, int $permId)
    {
        $Permission = PermissionMapper::getById($permId);
        if (RolePermissionMapper::isAssigned($roleId, $Permission['perm_desc'])) {
            if (RolePermissionMapper::unassign($roleId, $permId)) {
                Session::add('feedback_positive', 'Permissie verwijderd.');
                Redirect::to('UserManagement/Role/?id=' . $roleId);
            } else {
                Session::add('feedback_negative', 'Fout bij het verwijderen van de permissie.');
                Redirect::to('Error/Error');
            }
        } else {
            Session::add('feedback_negative', 'Niet toegewezen.');
            Redirect::to('Error/Error');
        }
    }
}
