<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\RolePermission;
use PortalCMS\Core\Security\Authorization\UserRoleMapper;
use PortalCMS\Core\HTTP\Session;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserManagementController
 * @package PortalCMS\Controllers
 */
class UserManagementController
{
    protected $templates;

//    private $requests = [
//        'deleteuser'           => 'POST',
//        'deleterole'           => 'POST',
//        'addrole'              => 'POST',
//        'setrolepermission'    => 'POST',
//        'deleterolepermission' => 'POST',
//        'assignrole'           => 'POST',
//        'unassignrole'         => 'POST',
//        'addNewUser'           => 'POST'
//    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function deleterole() : ResponseInterface
    {
        if (RoleMapper::delete((int) Request::post('role_id'))) {
            Session::add('feedback_positive', 'Rol verwijderd.');
            return new RedirectResponse('/UserManagement/Roles');
        }
        Session::add('feedback_negative', 'Fout bij het verwijderen van rol.');
        return new RedirectResponse('/Error/Error');
    }

    public static function addrole() : ResponseInterface
    {
        if (RoleMapper::create((string) Request::post('role_name'))) {
            Session::add('feedback_positive', 'Nieuwe rol aangemaakt.');
            return new RedirectResponse('/UserManagement/Roles');
        }
        Session::add('feedback_negative', 'Fout bij het aanmaken van nieuwe rol.');
        return new RedirectResponse('/Error/Error');
    }

    public static function setrolepermission()
    {
        RolePermission::assignPermission((int) Request::post('role_id'), (int) Request::post('perm_id'));
    }

    public static function deleterolepermission()
    {
        RolePermission::unassignPermission((int) Request::post('role_id'), (int) Request::post('perm_id'));
    }

    public static function assignrole() : ResponseInterface
    {
        $user_id = (int) Request::post('user_id');
        $role_id = (int) Request::post('role_id');
        if (UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is reeds toegewezen aan deze gebruiker.');
        } elseif (UserRoleMapper::assign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol toegewezen.');
            return new RedirectResponse('/UserManagement/Profile?id=' . $user_id);
        } else {
            Session::add('feedback_negative', 'Fout bij toewijzen van rol.');
        }
        return new RedirectResponse('/Error/Error');
    }

    public static function unassignrole() : ResponseInterface
    {
        $user_id = (int) Request::post('user_id');
        $role_id = (int) Request::post('role_id');
        if (!UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.');
        } elseif (UserRoleMapper::unassign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol voor gebruiker verwijderd.');
            return new RedirectResponse('/UserManagement/Profile?id=' . $user_id);
        } else {
            Session::add('feedback_negative', 'Fout bij verwijderen van rol voor gebruiker.');
        }
        return new RedirectResponse('/Error/Error');
    }

    public function users() : ResponseInterface
    {
        if (Authorization::hasPermission('user-management')) {
            echo $this->templates->render('Pages/UserManagement/Users/Index');
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function profile() : ResponseInterface
    {
        if (Authorization::hasPermission('user-management')) {
            echo $this->templates->render('Pages/UserManagement/Profile/Index');
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function roles() : ResponseInterface
    {
        if (Authorization::hasPermission('role-management')) {
            echo $this->templates->render('Pages/UserManagement/Roles/Index');
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function addUser() : ResponseInterface
    {
        if (Authorization::hasPermission('user-management')) {
            echo $this->templates->render('Pages/UserManagement/Users/AddUser');
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function role() : ResponseInterface
    {
        if (Authorization::hasPermission('user-management')) {
            echo $this->templates->render('Pages/UserManagement/Role/Index');
        }
        return new RedirectResponse('/Error/PermissionError');
    }
}
