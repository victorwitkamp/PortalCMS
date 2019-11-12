<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\User\PasswordReset;

/**
 * PasswordResetController
 * Submitting a password reset and resetting the password.
 */
class PasswordResetController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['requestPasswordReset'])) {
            if (PasswordReset::requestPasswordReset($_POST['user_name_or_email'])) {
                Redirect::to('/login');
            }
        }
        if (isset($_POST['resetSubmit'])) {
            if (PasswordReset::verifyPasswordReset($_POST['username'], $_POST['password_reset_hash'])) {
                $user_password_hash = password_hash(base64_encode($_POST['password']), PASSWORD_DEFAULT);
                if (PasswordReset::saveNewUserPassword($_POST['username'], $user_password_hash, $_POST['password_reset_hash'])) {
                    Redirect::to('login');
                } else {
                    Redirect::to('login/passwordReset.php');
                }
            }
        }
    }
}
