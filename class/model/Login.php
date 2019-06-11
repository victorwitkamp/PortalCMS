<?php
/**
 * Login
 *
 * Handles the login / logout process.
 */
class Login
{
    /**
     * Login process (for DEFAULT user accounts).
     *
     * @param $user_name string The user's name
     * @param $user_password string The user's password
     * @param $set_remember_me_cookie mixed Marker for usage of remember-me cookie feature
     *
     * @return bool success state
     */
    public static function loginWithPassword($user_name, $user_password, $set_remember_me_cookie = null)
    {
        // we do negative-first checks here, for simplicity empty username and empty password in one line
        if (empty($user_name) or empty($user_password)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        // checks if user exists, if login is not blocked (due to failed logins) and if password fits the hash
        $result = self::validateAndGetUser($user_name, $user_password);

        if (!$result) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($result->user_last_failed_login > 0) {
            UserMapper::resetFailedLoginsByUsername($result->user_name);
        }

        UserMapper::saveTimestampByUsername($result->user_name);

        if ($set_remember_me_cookie) {
            self::setRememberMe($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        self::setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $result->user_fbid
        );

        // return true to make clear the login was successful
        // maybe do this in dependence of setSuccessfulLoginIntoSession ?
        Session::add('feedback_positive', Text::get('FEEDBACK_LOGIN_SUCCESSFUL'));

        return true;
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * TODO add throttling here ?
     *
     * @param $cookie string The cookie "remember_me"
     *
     * @return bool success state
     */
    public static function loginWithCookie($cookie)
    {
        if (!$cookie) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // before list(), check it can be split into 3 strings.
        if (count(explode(':', $cookie)) !== 3) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // check cookie's contents, check if cookie contents belong together or token is empty
        list ($user_id, $token, $hash) = explode(':', $cookie);

        $user_id = Encryption::decrypt($user_id);

        if ($hash !== hash('sha256', $user_id.':'.$token) or empty($token) or empty($user_id)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        $result = UserMapper::getByIdAndToken($user_id, $token);

        if (!$result) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $result->user_fbid);
        UserMapper::saveTimestampByUsername($result->user_name);

        // NOTE: we don't set another remember_me-cookie here as the current cookie should always
        // be invalid after a certain amount of time, so the user has to login with username/password
        // again from time to time. This is good and safe ! ;)

        Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
        return true;
    }

    public static function loginWithFacebook($fbid)
    {
        if (empty($fbid)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
            return false;
        }
        $result = UserMapper::getByFbid($fbid);
        if (!$result) {
            Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
            return false;
        }
        self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $fbid);
        UserMapper::saveTimestampByUsername($result->user_name);
        Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
        return true;
    }


    /**
     * Validates the inputs of the users, checks if password is correct etc.
     * If successful, user is returned
     *
     * @param $user_name
     * @param $user_password
     *
     * @return bool|mixed
     */
    private static function validateAndGetUser($user_name, $user_password)
    {
        // Brute force attack mitigation:
        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (Session::get('failed-login-count') >= 3) {
            if (Session::get('last-failed-login') > (time() - 30)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
                return false;
            }
        }

        $result = UserMapper::getByUserName($user_name);

        if (!$result) {
            self::incrementUserNotFoundCounter();
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if ($result->user_failed_logins >= 3) {
            if (strtotime($result->user_last_failed_login) > (strtotime(date('Y-m-d H:i:s')) - 30)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
                return false;
            }
        }

        // if hash of provided password does NOT match the hash in the database: +1 failed-login counter
        if (!password_verify($user_password, $result->user_password_hash)) {
            UserMapper::setFailedLoginByUsername($result->user_name);
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        // if user is not active (= has not verified account by verification mail)
        if ($result->user_active != 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return false;
        }

        // reset the user not found counter
        self::resetUserNotFoundCounter();
        return $result;
    }

    /**
     * Reset the failed-login-count to 0.
     * Reset the last-failed-login to an empty string.
     */
    private static function resetUserNotFoundCounter()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    /**
     * Increment the failed-login-count by 1.
     * Add timestamp to last-failed-login.
     */
    private static function incrementUserNotFoundCounter()
    {
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }

    /**
     * Log out process: delete cookie, delete session
     */
    public static function logout()
    {
        $user_id = Session::get('user_id');

        UserMapper::clearRememberMeToken($user_id);
        // if (UserMapper::clearRememberMeToken($user_id)) {
            if (Cookie::delete()) {
                if (Session::destroy()) {
                    Session::init();
                    Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                    return true;
                }
            }
        // }
        return false;
    }

    /**
     * The real login process: The user's data is written into the session.
     * Cheesy name, maybe rename. Also maybe refactoring this, using an array.
     *
     * @param $user_id
     * @param $user_name
     * @param $user_email
     * @param $user_account_type
     */
    public static function setSuccessfulLoginIntoSession($user_id, $user_name, $user_email, $user_account_type, $user_fbid)
    {
        Session::init();

        // remove old and regenerate session ID.
        // It's important to regenerate session on sensitive actions,
        // and to avoid fixated session.
        // e.g. when a user logs in
        session_regenerate_id(true);
        // $_SESSION = array();

        Session::set('user_id', $user_id);
        Session::set('user_name', $user_name);
        Session::set('user_email', $user_email);
        Session::set('user_account_type', $user_account_type);
        Session::set('user_provider_type', 'DEFAULT');
        Session::set('user_fbid', $user_fbid);

        // get and set avatars
        // Session::set('user_avatar_file', AvatarModel::getPublicUserAvatarFilePathByUserId($user_id));
        // Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($user_email));

        Session::set('user_logged_in', true);
        Session::updateSessionId($user_id, session_id());

        Cookie::setSessionCookie();
    }

    /**
     * Write remember-me token into database and into cookie
     * Maybe splitting this into database and cookie part ?
     *
     * @param $user_id
     */
    public static function setRememberMe($user_id)
    {
        // generate 64 char random string
        $token = hash('sha256', mt_rand());

        UserMapper::updateRememberMeToken($user_id, $token);

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        $cookie_string_first_part = Encryption::encrypt($user_id).':'.$token;
        $cookie_string_hash       = hash('sha256', $user_id.':'.$token);
        $cookie_string            = $cookie_string_first_part.':'.$cookie_string_hash;

        Cookie::setRememberMe($cookie_string);
    }



    /**
     * Returns the current state of the user's login
     *
     * @return bool user's login status
     */
    public static function isUserLoggedIn()
    {
        return Session::userIsLoggedIn();
    }
}
