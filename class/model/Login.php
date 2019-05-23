<?php
/**
 * LoginModel
 *
 * Handles the login / logout process.
 */
class LoginModel
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
        if (empty($user_name) OR empty($user_password)) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        // checks if user exists, if login is not blocked (due to failed logins) and if password fits the hash
        $result = self::validateAndGetUser($user_name, $user_password);

        if (!$result) {
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($result->user_last_failed_login > 0) {
            self::resetFailedLoginCounterOfUser($result->user_name);
        }

        self::saveTimestampOfLoginOfUser($result->user_name);

        if ($set_remember_me_cookie) {
            self::setRememberMe($result->user_id);
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        self::setSuccessfulLoginIntoSession(
            $result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $result->user_fbid
        );
        $_SESSION['response'][] = array("status"=>"success", "message"=>'Ingelogd met wachtwoord');
        UserActivity::registerUserActivity('login');

        // return true to make clear the login was successful
        // maybe do this in dependence of setSuccessfulLoginIntoSession ?
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
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // before list(), check it can be split into 3 strings.
        if (count(explode(':', $cookie)) !== 3) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        // check cookie's contents, check if cookie contents belong together or token is empty
        list ($user_id, $token, $hash) = explode(':', $cookie);

        $user_id = Encryption::decrypt($user_id);

        if ($hash !== hash('sha256', $user_id.':'.$token) OR empty($token) OR empty($user_id)) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        $result = User::getUserByUserIdAndToken($user_id, $token);

        if (!$result) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $result->user_fbid);
        self::saveTimestampOfLoginOfUser($result->user_name);

        // NOTE: we don't set another remember_me-cookie here as the current cookie should always
        // be invalid after a certain amount of time, so the user has to login with username/password
        // again from time to time. This is good and safe ! ;)

        $_SESSION['response'][] = array("status"=>"success", "message"=>Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
        return true;
    }

    public static function loginWithFacebook($fbid)
    {
        if (empty($fbid)) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
            return false;
        }
        $result = User::getUserByFbid($fbid);
        if ($result) {
            self::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $fbid);
            self::saveTimestampOfLoginOfUser($result->user_name);
            $_SESSION['response'][] = array("status"=>"success", "message"=>Text::get('FEEDBACK_SUCCESFUL_FACEBOOK_LOGIN'));
            return true;
        } else {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
            return false;
        }
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
        if (Session::get('failed-login-count') >= 3 AND (Session::get('last-failed-login') > (time() - 30))) {
            // Session::init();
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            // Redirect::home();
            // exit();
            return false;
        }

        $result = User::getUserByUsername($user_name);

        if (!$result) {
            self::incrementUserNotFoundCounter();
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        // TODO NON NUMERIC ERROR
        // [23-May-2019 01:21:43 Europe/Amsterdam] PHP Notice:  A non well formed numeric value encountered in /home/vhosting/d/vhost0049425/domains/beukonline.nl/htdocs/portal/class/model/Login.php on line 154
        $last_failed = $result->user_last_failed_login;
        $unix_last_failed = strtotime($last_failed);
        $now = date('Y-m-d H:i:s');
        $unix_now = strtotime($now);
        if (($result->user_failed_logins >= 3) AND ($unix_last_failed > ($unix_now - 30))) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return false;
        }

        // if hash of provided password does NOT match the hash in the database: +1 failed-login counter
        if (!password_verify($user_password, $result->user_password_hash)) {
            self::incrementFailedLoginCounterOfUser($result->user_name);
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        // if user is not active (= has not verified account by verification mail)
        if ($result->user_active != 1) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
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
        self::deleteCookie();
        Session::destroy();
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
        $_SESSION = array();

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

        // set session cookie setting manually,
        // Why? because you need to explicitly set session expiry, path, domain, secure, and HTTP.
        // @see https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Cookies
        setcookie(
            session_name(),
            session_id(),
            time() + Config::get('SESSION_RUNTIME'),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            Config::get('COOKIE_SECURE'),
            Config::get('COOKIE_HTTP')
        );
    }


    /**
     * Increments the failed-login counter of a user
     *
     * @param $user_name
     */
    public static function incrementFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_failed_login' => date('Y-m-d H:i:s')));
    }

    /**
     * Resets the failed-login counter of a user back to 0
     *
     * @param $user_name
     */
    public static function resetFailedLoginCounterOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_failed_logins = 0, user_last_failed_login = NULL
                WHERE user_name = :user_name AND user_failed_logins != 0
                LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name));
    }

    /**
     * Write timestamp of this login into database (we only write a "real" login via login form into the database,
     * not the session-login on every page request
     *
     * @param $user_name
     */
    public static function saveTimestampOfLoginOfUser($user_name)
    {
        $sql = "UPDATE users
                SET user_last_login_timestamp = :user_last_login_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_name' => $user_name, ':user_last_login_timestamp' => date('Y-m-d H:i:s')));
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
        $random_token_string = hash('sha256', mt_rand());

        // write that token into database
        $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
        $sth = DB::conn()->prepare($sql);
        $sth->execute(array(':user_remember_me_token' => $random_token_string, ':user_id' => $user_id));

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        $cookie_string_first_part = Encryption::encrypt($user_id).':'.$random_token_string;
        $cookie_string_hash       = hash('sha256', $user_id.':'.$random_token_string);
        $cookie_string            = $cookie_string_first_part.':'.$cookie_string_hash;

        // set cookie, and make it available only for the domain created on (to avoid XSS attacks, where the
        // attacker could steal your remember-me cookie string and would login itself).
        // If you are using HTTPS, then you should set the "secure" flag (the second one from right) to true, too.
        // @see http://www.php.net/manual/en/function.setcookie.php
        setcookie(
            'remember_me',
            $cookie_string,
            time() + Config::get('COOKIE_RUNTIME'),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            Config::get('COOKIE_SECURE'),
            Config::get('COOKIE_HTTP')
        );
    }

    /**
     * Deletes the cookie
     * It's necessary to split deleteCookie() and logout() as cookies are deleted without logging out too!
     * Sets the remember-me-cookie to ten years ago (3600sec * 24 hours * 365 days * 10).
     * that's obviously the best practice to kill a cookie @see http://stackoverflow.com/a/686166/1114320
     *
     * @param string $user_id
     */
    public static function deleteCookie($user_id = null)
    {
        // is $user_id was set, then clear remember_me token in database
        if (isset($user_id)) {
            $sql = "UPDATE users SET user_remember_me_token = :user_remember_me_token WHERE user_id = :user_id LIMIT 1";
            $sth = DB::conn()->prepare($sql);
            $sth->execute(array(':user_remember_me_token' => NULL, ':user_id' => $user_id));
        }

        // delete remember_me cookie in browser
        setcookie(
            'remember_me',
            false,
            time() - (3600 * 24 * 3650),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            Config::get('COOKIE_SECURE'),
            Config::get('COOKIE_HTTP')
        );
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
