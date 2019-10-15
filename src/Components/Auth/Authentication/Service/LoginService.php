<?php

class LoginService
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
        if (empty($user_name) || empty($user_password)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        $result = Auth::validateAndGetUser($user_name, $user_password);

        if (!$result) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        if ($result->user_last_failed_login > 0) {
            UserMapper::resetFailedLoginsByUsername($result->user_name);
        }

        UserMapper::saveTimestampByUsername($result->user_name);

        if ($set_remember_me_cookie) {
            Auth::setRememberMe($result->user_id);
        }

        Auth::setSuccessfulLoginIntoSession(
            $result->user_id,
            $result->user_name,
            $result->user_email,
            $result->user_account_type,
            $result->user_fbid
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

        if ($hash !== hash('sha256', $user_id.':'.$token) || empty($token) || empty($user_id)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        $result = UserMapper::getByIdAndToken($user_id, $token);

        if (!$result) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return false;
        }

        Auth::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $result->user_fbid);
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
        Auth::setSuccessfulLoginIntoSession($result->user_id, $result->user_name, $result->user_email, $result->user_account_type, $fbid);
        UserMapper::saveTimestampByUsername($result->user_name);
        Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
        return true;
    }
}