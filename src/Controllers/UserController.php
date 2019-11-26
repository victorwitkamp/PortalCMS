<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Security\Authorization\UserRoleMapper;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

class UserController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['assignrole'])) {
            self::assignRole($_POST['user_id'], $_POST['role_id']);
        }
        if (isset($_POST['unassignrole'])) {
            self::unassignRole($_POST['user_id'], $_POST['role_id']);
        }

        // if (isset($_POST['deleteuser'])) {
        //     $this->deleteUser($_POST['user_id']);
        // }
    }

    public static function assignRole($user_id, $role_id): bool
    {
        if (UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is reeds toegewezen aan deze gebruiker.');
            Redirect::to('includes/error.php');
            return false;
        }
        if (UserRoleMapper::assign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol toegewezen.');
            Redirect::to('settings/Profile?id=' . $user_id);
            return true;
        }
        Session::add('feedback_negative', 'Fout bij toewijzen van rol.');
        Redirect::to('includes/error.php');
        return false;
    }

    public static function unassignRole($user_id, $role_id): bool
    {
        if (!UserRoleMapper::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', 'Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.');
            Redirect::to('includes/error.php');
            return false;
        }
        if (UserRoleMapper::unassign($user_id, $role_id)) {
            Session::add('feedback_positive', 'Rol voor gebruiker verwijderd.');
            Redirect::to('settings/Profile?id=' . $user_id);
            return true;
        }
        Session::add('feedback_negative', 'Fout bij verwijderen van rol voor gebruiker.');
        Redirect::to('includes/error.php');
        return false;
    }
}
