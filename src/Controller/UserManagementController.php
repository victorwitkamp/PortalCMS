<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\HTTP\Redirect;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Core\Security\Authorization\RoleMapper;
use App\Core\Security\Authorization\RolePermission;
use App\Core\Security\Authorization\UserRoleMapper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/UserManagement", name="usermanagement")
 */
class UserManagementController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    public function deleteuser()
    {
        return $this->redirectToRoute('errornotfound');
    }

    public function deleterole()
    {
        if (RoleMapper::delete((int)$this->request->get('role_id'))) {
            $this->addFlash('success','Rol verwijderd.');
            Redirect::to('UserManagement/Roles');
        } else {
            $this->addFlash('danger','Fout bij het verwijderen van rol.');
            return $this->redirectToRoute('/Error');
        }
    }

    public function addrole() : Response
    {
        if (RoleMapper::create((string)$this->request->get('role_name'))) {
            $this->addFlash('success','Nieuwe rol aangemaakt.');
            return $this->redirectToRoute('usermanagementroles');
        }
        $this->addFlash('danger','Fout bij het aanmaken van nieuwe rol.');
        return $this->redirectToRoute('error');
    }

    public function setrolepermission()
    {
        RolePermission::assignPermission((int)$this->request->get('role_id'), (int)$this->request->get('perm_id'));
    }

    public function deleterolepermission()
    {
        RolePermission::unassignPermission((int)$this->request->get('role_id'), (int)$this->request->get('perm_id'));
    }

    public function assignrole(): Response
    {
        $user_id = (int)$this->request->get('user_id');
        $role_id = (int)$this->request->get('role_id');
        if (UserRoleMapper::isAssigned($user_id, $role_id)) {
            $this->addFlash('danger','Rol is reeds toegewezen aan deze gebruiker.');
        } elseif (UserRoleMapper::assign($user_id, $role_id)) {
            $this->addFlash('success','Rol toegewezen.');
            return $this->redirectToRoute('UserManagement/Profile?id=' . $user_id);
        } else {
            $this->addFlash('danger','Fout bij toewijzen van rol.');
        }
        return $this->redirectToRoute('error');
    }

    public function unassignrole(): Response
    {
        $user_id = (int)$this->request->get('user_id');
        $role_id = (int)$this->request->get('role_id');
        if (!UserRoleMapper::isAssigned($user_id, $role_id)) {
            $this->addFlash('danger','Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.');
        } elseif (UserRoleMapper::unassign($user_id, $role_id)) {
            $this->addFlash('success','Rol voor gebruiker verwijderd.');
            return $this->redirectToRoute('UserManagement/Profile?id=' . $user_id);
        } else {
            $this->addFlash('danger','Fout bij verwijderen van rol voor gebruiker.');
        }
        return $this->redirectToRoute('error');
    }

    /**
     * @Route("/Users", name="users")
     */
    public function users() : Response
    {
        if (Authorization::hasPermission('user-management')) {

            return $this->render('UserManagement/Users/Index.html.twig');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Profile", name="profile")
     */
    public function profile() : Response
    {
        if (Authorization::hasPermission('user-management')) {

            return $this->render('Pages/UserManagement/Profile/Index');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Roles", name="roles")
     */
    public function roles() : Response
    {
        if (Authorization::hasPermission('role-management')) {

            return $this->render('Pages/UserManagement/Roles/Index');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/AddUser", name="adduser")
     */
    public function addUser() : Response
    {
        if (Authorization::hasPermission('user-management')) {

            return $this->render('Pages/UserManagement/Users/AddUser');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Role", name="role")
     */
    public function role() : Response
    {
        if (Authorization::hasPermission('user-management')) {

            return $this->render('Pages/UserManagement/Role/Index');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }
}
