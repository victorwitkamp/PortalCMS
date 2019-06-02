<?php

/**
 * PasswordResetController
 * Submitting a password reset and resetting the password.
 */
class PasswordResetController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['requestPasswordReset'])) {
            if (PasswordReset::requestPasswordReset($_POST['user_name_or_email'])) {
                Redirect::redirectPage("login/login.php");
            }
        }
        if (isset($_POST['resetSubmit'])) {
            if (PasswordReset::verifyPasswordReset($_POST['username'], $_POST['password_reset_hash'])) {
                $user_password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if (PasswordReset::saveNewUserPassword($_POST['username'], $user_password_hash, $_POST['password_reset_hash'])) {
                    Redirect::login();
                } else {
                    Redirect::redirectPage("login/passwordReset.php");
                }
            }
        }
    }
}