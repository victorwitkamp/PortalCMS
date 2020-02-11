<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\RolePermission;
use PortalCMS\Core\Security\Authorization\UserRoleMapper;
use PortalCMS\Core\Session\Session;

class UserManagementController extends Controller
{
    /**
     * Form POST requests
     * @var array $requests
     */
    private $requests = [
        'deleteuser' => 'POST',
        'deleterole' => 'POST',
        'addrole' => 'POST',
        'setrolepermission' => 'POST',
        'deleterolepermission' => 'POST',
        'assignrole' => 'POST',
        'unassignrole' => 'POST',
        'addNewUser' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function users()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Users/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function profile()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Profile/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function roles()
    {
        if (Authorization::hasPermission('role-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Roles/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function addUser()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Users/AddUser');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function role()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Role/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public static function deleteuser()
    {
        // Not inplemented yet
        $controller = new ErrorController();
        $controller->notFound();
    }

    public static function deleterole()
    {
        if (RoleMapper::delete((int) $_POST['role_id'])) {
            Session::add('feedback_positive', 'Rol verwijderd.');
            Redirect::to('UserManagement/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het verwijderen van rol.');
            Redirect::to('Error/Error');
        }
    }

    public static function addrole()
    {
        if (RoleMapper::create($_POST['role_name'])) {
            Session::add('feedback_positive', 'Nieuwe rol aangemaakt.');
            Redirect::to('UserManagement/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het aanmaken van nieuwe rol.');
            Redirect::to('Error/Error');
        }
    }

    public static function setrolepermission()
    {
        RolePermission::assignPermission((int) $_POST['role_id'], (int) $_POST['perm_id']);
    }

    public static function deleterolepermission()
    {
        RolePermission::unassignPermission((int) $_POST['role_id'], (int) $_POST['perm_id']);
    }

    public static function assignrole(): bool
    {
        $user_id = (int) $_POST['user_id'];
        $role_id = (int) $_POST['role_id'];
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

    public static function unassignrole(): bool
    {
        $user_id = (int) $_POST['user_id'];
        $role_id = (int) $_POST['role_id'];
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

//    public static function addNewUser() : ?int
//    {
//
//    }
}
