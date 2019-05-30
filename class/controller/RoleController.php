<?php

class RoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['deleterole'])) {
            Role::delete($_POST['role_id']);
        }
        if (isset($_POST['addrole'])) {
            Role::create($_POST['role_name']);
        }
        if (isset($_POST['setrolepermission'])) {
            if (Permission::assign($_POST['role_id'], $_POST['perm_id'])) {
                Redirect::redirectPage("settings/user-management/role.php?role_id=".$_POST['role_id']);
            }
        }
        if (isset($_POST['deleterolepermission'])) {
            if (Permission::unassign($RoleID, $_POST['perm_id'])) {
                Redirect::redirectPage("settings/user-management/role.php?role_id=".$_POST['role_id']);
            }
        }
    }
}