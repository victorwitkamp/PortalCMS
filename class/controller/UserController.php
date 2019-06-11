<?php
class UserController extends Controller
{
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

    public static function assignRole($user_id, $role_id)
    {
        if (UserRole::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', "Rol is reeds toegewezen aan deze gebruiker.");
            Redirect::error();
            return false;
        }
        if (UserRole::assign($user_id, $role_id)) {
            Session::add('feedback_positive', "Rol toegewezen.");
            Redirect::to('settings/user-management/profile.php?id='.$user_id);
            return true;
        }
        Session::add('feedback_negative', "Fout bij toewijzen van rol.");
        Redirect::error();
        return false;
    }

    public static function unassignRole($user_id, $role_id)
    {
        if (!UserRole::isAssigned($user_id, $role_id)) {
            Session::add('feedback_negative', "Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.");
            Redirect::error();
            return false;
        }
        if (UserRole::unassign($user_id, $role_id)) {
            Session::add('feedback_positive', "Rol voor gebruiker verwijderd.");
            Redirect::to('settings/user-management/profile.php?id='.$user_id);
            return true;
        }
        Session::add('feedback_negative', "Fout bij verwijderen van rol voor gebruiker.");
        Redirect::error();
        return false;
    }
}