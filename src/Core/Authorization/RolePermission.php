<?php

namespace PortalCMS\Core\Authorization;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

class RolePermission
{

    public static function assignPermission($role_id, $perm_id)
    {
        $Permission = PermissionMapper::getById($perm_id);
        if (RolePermissionMapper::isAssigned($role_id, $Permission['perm_desc'])) {
            Session::add('feedback_negative', 'Reeds toegewezen.');
            Redirect::error();
        } else {
            if (RolePermissionMapper::assign($role_id, $perm_id)) {
                Session::add('feedback_positive', 'Permissie toegewezen.');
                Redirect::to('settings/user-management/role.php?role_id=' .$role_id);
            } else {
                Session::add('feedback_negative', 'Fout bij het toewijzen van de permissie.');
                Redirect::error();
            }
        }
    }

    public static function unassignPermission($role_id, $perm_id)
    {
        $Permission = PermissionMapper::getById($perm_id);
        if (!RolePermissionMapper::isAssigned($role_id, $Permission['perm_desc'])) {
            Session::add('feedback_negative', 'Niet toegewezen.');
            Redirect::error();
        } else {
            if (RolePermissionMapper::unassign($role_id, $perm_id)) {
                Session::add('feedback_positive', 'Permissie verwijderd.');
                Redirect::to('settings/user-management/role.php?role_id=' .$role_id);
            } else {
                Session::add('feedback_negative', 'Fout bij het verwijderen van de permissie.');
                Redirect::error();
            }
        }
    }

}
