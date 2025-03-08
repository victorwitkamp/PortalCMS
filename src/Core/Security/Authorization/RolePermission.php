<?php


declare(strict_types=1);

namespace App\Core\Security\Authorization;

use App\Core\HTTP\Redirect;

class RolePermission
{

    public function assignPermission(int $roleId, int $permId)
    {
        $Permission = PermissionMapper::getById($permId);
        if (RolePermissionMapper::isAssigned($roleId, $Permission['perm_desc'])) {
            $this->addFlash('danger','Reeds toegewezen.');
            return $this->redirectToRoute('/Error');
        } elseif (RolePermissionMapper::assign($roleId, $permId)) {
            $this->addFlash('success','Permissie toegewezen.');
            Redirect::to('UserManagement/Role/?id=' . $roleId);
        } else {
            $this->addFlash('danger','Fout bij het toewijzen van de permissie.');
            return $this->redirectToRoute('/Error');
        }
    }

    public function unassignPermission(int $roleId, int $permId)
    {
        $Permission = PermissionMapper::getById($permId);
        if (RolePermissionMapper::isAssigned($roleId, $Permission['perm_desc'])) {
            if (RolePermissionMapper::unassign($roleId, $permId)) {
                $this->addFlash('success','Permissie verwijderd.');
                Redirect::to('UserManagement/Role/?id=' . $roleId);
            } else {
                $this->addFlash('danger','Fout bij het verwijderen van de permissie.');
                return $this->redirectToRoute('/Error');
            }
        } else {
            $this->addFlash('danger','Niet toegewezen.');
            return $this->redirectToRoute('/Error');
        }
    }
}
