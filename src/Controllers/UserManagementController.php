<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\RolePermission;
use PortalCMS\Core\Security\Authorization\UserRoleMapper;
use PortalCMS\Core\HTTP\Session;

/**
 * Class UserManagementController
 * @package PortalCMS\Controllers
 */
class UserManagementController
{
    protected $templates;

    private $requests = [
        'deleteuser'           => 'POST',
        'deleterole'           => 'POST',
        'addrole'              => 'POST',
        'setrolepermission'    => 'POST',
        'deleterolepermission' => 'POST',
        'assignrole'           => 'POST',
        'unassignrole'         => 'POST',
        'addNewUser'           => 'POST'
    ];

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public static function deleterole()
    {
        if (RoleMapper::delete((int) Request::post('role_id'))) {
            Session::add('feedback_positive', 'Rol verwijderd.');
            Redirect::to('UserManagement/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het verwijderen van rol.');
            Redirect::to('Error/Error');
        }
    }

    public static function addrole()
    {
        if (RoleMapper::create((string) Request::post('role_name'))) {
            Session::add('feedback_positive', 'Nieuwe rol aangemaakt.');
            Redirect::to('UserManagement/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het aanmaken van nieuwe rol.');
            Redirect::to('Error/Error');
        }
    }

    public static function setrolepermission()
    {
        RolePermission::assignPermission((int) Request::post('role_id'), (int) Request::post('perm_id'));
    }

    public static function deleterolepermission()
    {
        RolePermission::unassignPermission((int) Request::post('role_id'), (int) Request::post('perm_id'));
    }

    /**
     */
    public static function assignrole(): bool
    {
        $user_id = (int) Request::post('user_id');
        $role_id = (int) Request::post('role_id');
        if (UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is reeds toegewezen aan deze gebruiker.');
        } elseif (UserRoleMapper::assign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol toegewezen.');
            Redirect::to('UserManagement/Profile?id=' . $user_id);
            return true;
        } else {
            Session::add('feedback_negative', 'Fout bij toewijzen van rol.');
        }
        Redirect::to('Error/Error');
        return false;
    }

    /**
     * @return bool
     */
    /**
     * @return bool
     */
    /**
     */
    public static function unassignrole(): bool
    {
        $user_id = (int) Request::post('user_id');
        $role_id = (int) Request::post('role_id');
        if (!UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.');
        } elseif (UserRoleMapper::unassign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol voor gebruiker verwijderd.');
            Redirect::to('UserManagement/Profile?id=' . $user_id);
            return true;
        } else {
            Session::add('feedback_negative', 'Fout bij verwijderen van rol voor gebruiker.');
        }
        Redirect::to('Error/Error');
        return false;
    }

    public function users() : void
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Users/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function profile() : void
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Profile/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function roles() : void
    {
        if (Authorization::hasPermission('role-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Roles/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function addUser() : void
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Users/AddUser');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function role() : void
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Role/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }
}
