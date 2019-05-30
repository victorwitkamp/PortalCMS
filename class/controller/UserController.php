<?php
class UserController extends Controller
{
    public function __construct()
    {
        if (isset($_POST['assignrole'])) {
            Role::assign($_POST['user_id'], $_POST['role_id']);
        }
        if (isset($_POST['unassignrole'])) {
            Role::unassign($_POST['user_id'], $_POST['role_id']);
        }

        // if (isset($_POST['deleteuser'])) {
        //     $this->deleteUser($_POST['user_id']);
        // }
    }
}