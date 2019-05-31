<?php

class PasswordResetController extends Controller
{
    public function __construct() {
        if (isset($_POST['requestPasswordReset'])) {
            PasswordReset::requestPasswordReset($_POST['user_name_or_email']);
        }
        if (isset($_POST['resetSubmit'])) {
            if (PasswordReset::verifyPasswordReset(
                $_POST['username'], $_POST['user_password_reset_hash'])
            ) {
                $user_password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if (PasswordReset::saveNewUserPassword($_POST['username'], $user_password_hash, $_POST['user_password_reset_hash']))
                {
                    Session::add('feedback_positive', 'Opgeslagen');
                    Redirect::login();

                } else {
                 Redirect::redirectPage("login/passwordReset.php");

                }
            }
        }
    }
}