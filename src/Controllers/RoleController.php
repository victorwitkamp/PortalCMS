<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\RolePermission;
use PortalCMS\Core\Session\Session;

class RoleController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['deleterole'])) {
            self::delete($_POST['role_id']);
        }
        if (isset($_POST['addrole'])) {
            self::create($_POST['role_name']);
        }
        if (isset($_POST['setrolepermission'])) {
            RolePermission::assignPermission($_POST['role_id'], $_POST['perm_id']);
        }
        if (isset($_POST['deleterolepermission'])) {
            RolePermission::unassignPermission($_POST['role_id'], $_POST['perm_id']);
        }
    }

    public static function create($role_name)
    {
        if (RoleMapper::create($role_name)) {
            Session::add('feedback_positive', 'Nieuwe rol aangemaakt.');
            Redirect::to('Settings/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het aanmaken van nieuwe rol.');
            Redirect::to('includes/error.php');
        }
    }

    public static function delete($role_id)
    {
        if (RoleMapper::delete($role_id)) {
            Session::add('feedback_positive', 'Rol verwijderd.');
            Redirect::to('Settings/Roles');
        } else {
            Session::add('feedback_negative', 'Fout bij het verwijderen van rol.');
            Redirect::to('includes/error.php');
        }
    }
}
