<?php

namespace PortalCMS\Core\Authentication;

use Exception;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\Encryption\Encryption;

/**
 * Class Auth
 * Checks if user is logged in, if not then sends the user to "yourdomain.com/login".
 */
class Authentication
{
    /**
     * The normal authentication flow, just check if the user is logged in (by looking into the session).
     * If user is not, then he will be redirected to login page and the application is hard-stopped via exit().
     */
    public static function checkAuthentication()
    {
        // initialize the session (if not initialized yet)
        Session::init();

        // if user is NOT logged in...
        // (if user IS logged in the application will not run the code below and therefore just go on)
        if (!self::userIsLoggedIn()) {

            // ... then treat user as "not logged in", destroy session, redirect to login page
            Session::destroy();

            // send the user to the login form page, but also add the current page's URI (the part after the base URL)
            // as a parameter argument, making it possible to send the user back to where he/she came from after a
            // successful login

            // header('location: ' . Config::get('URL') . 'login/?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            // Redirect::to('login/?redirect='.ltrim(urlencode($_SERVER['REQUEST_URI']), '/'));
            Redirect::to('login/?redirect=' . urlencode($_SERVER['REQUEST_URI']));

            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            // this is not optimal and will be fixed in future releases
            exit();
        }

        // Hook to check is a cookie exists and if it matches a remember me token in the database.
        // if (!Cookie::isValid()) {

        // }
    }

    /**
     * Checks if the user is logged in or not
     *
     * @return bool user's login status
     */
    public static function userIsLoggedIn(): bool
    {
        return (Session::get('user_logged_in') ? true : false);
    }

    /**
     * Validates the inputs of the users, checks if password is correct etc.
     * If successful, user is returned
     *
     * @param $user_name
     * @param $user_password
     *
     * @return mixed
     */
    public static function validateAndGetUser($user_name, $user_password)
    {
        // Brute force attack mitigation:
        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if ((Session::get('failed-login-count') >= 3) && Session::get('last-failed-login') > (time() - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            return false;
        }

        $result = UserPDOReader::getByUsername($user_name);

        if (!$result) {
            self::incrementUserNotFoundCounter();
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (($result->user_failed_logins >= 3) && strtotime($result->user_last_failed_login) > (strtotime(date('Y-m-d H:i:s')) - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return false;
        }

        // if hash of provided password does NOT match the hash in the database: +1 failed-login counter
        if (!password_verify(base64_encode($user_password), $result->user_password_hash)) {
            UserPDOWriter::setFailedLoginByUsername($result->user_name);
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        if ($result->user_active != 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return false;
        }

        self::resetUserNotFoundCounter();
        return $result;
    }

    /**
     * Reset the failed-login-count to 0.
     * Reset the last-failed-login to an empty string.
     */
    public static function resetUserNotFoundCounter()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    /**
     * Increment the failed-login-count by 1.
     * Add timestamp to last-failed-login.
     */
    public static function incrementUserNotFoundCounter()
    {
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }


    /**
     * The real login process: The user's data is written into the session.
     * Cheesy name, maybe rename. Also maybe refactoring this, using an array.
     *
     * @param $user_id
     * @param $user_name
     * @param $user_email
     * @param $user_account_type
     * @param $user_fbid
     */
    public static function setSuccessfulLoginIntoSession($user)
    {
        Session::init();

        // remove old and regenerate session ID.
        // It's important to regenerate session on sensitive actions,
        // and to avoid fixated session.
        // e.g. when a user logs in
        session_regenerate_id(true);
        // $_SESSION = array();

        Session::set('user_id', $user->user_id);
        Session::set('user_name', $user->user_name);
        Session::set('user_email', $user->user_email);
        Session::set('user_account_type', $user->user_account_type);
        Session::set('user_provider_type', 'DEFAULT');
        Session::set('user_fbid', $user->user_fbid);

        // get and set avatars
        // Session::set('user_avatar_file', AvatarModel::getPublicUserAvatarFilePathByUserId($user_id));
        // Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($user_email));

        Session::set('user_logged_in', true);
        UserPDOWriter::updateSessionId($user->user_id, session_id());
        UserPDOWriter::saveTimestampByUsername($user->user_name);
        Cookie::setSessionCookie();
    }

    /**
     * Write remember-me token into database and into cookie
     * Maybe splitting this into database and cookie part ?
     *
     * @param $user_id
     * @return bool
     */
    public static function setRememberMe($user_id)
    {
        // generate 64 char random string
        $token = hash('sha256', mt_rand());

        UserPDOWriter::updateRememberMeToken($user_id, $token);

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        try {
            $cookie_string_first_part = Encryption::encrypt($user_id) . ':' . $token;
        } catch (Exception $e) {
            return false;
        }
        $cookie_string_hash       = hash('sha256', $user_id . ':' . $token);
        $cookie_string            = $cookie_string_first_part . ':' . $cookie_string_hash;

        Cookie::setRememberMe($cookie_string);
    }
}
