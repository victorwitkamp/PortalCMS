<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

class RolePermission
{

    public static function assignPermission(int $role_id, int $perm_id)
    {
        $Permission = PermissionMapper::getById($perm_id);
        if (RolePermissionMapper::isAssigned($role_id, $Permission['perm_desc'])) {
            Session::add('feedback_negative', 'Reeds toegewezen.');
            Redirect::to('Error/Error');
        } else {
            if (RolePermissionMapper::assign($role_id, $perm_id)) {
                Session::add('feedback_positive', 'Permissie toegewezen.');
                Redirect::to('UserManagement/Role/?id=' . $role_id);
            } else {
                Session::add('feedback_negative', 'Fout bij het toewijzen van de permissie.');
                Redirect::to('Error/Error');
            }
        }
    }

    public static function unassignPermission(int $role_id, int $perm_id)
    {
        $Permission = PermissionMapper::getById($perm_id);
        if (RolePermissionMapper::isAssigned($role_id, $Permission['perm_desc'])) {
            if (RolePermissionMapper::unassign($role_id, $perm_id)) {
                Session::add('feedback_positive', 'Permissie verwijderd.');
                Redirect::to('UserManagement/Role/?id=' . $role_id);
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
