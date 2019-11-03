<?php

namespace PortalCMS\Core\Authentication\Service;

use Exception;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\Encryption\Encryption;
use PortalCMS\Core\Authentication\Authentication;

class LoginService
{
    /**
     * Login process (for DEFAULT user accounts).
     *
     * @param string $user_name The user's name
     * @param string $user_password The user's password
     * @param mixed $set_remember_me_cookie Marker for usage of remember-me cookie feature
     *
     * @return bool success state
     * @throws Exception
     */
    public static function loginWithPassword($user_name, $user_password, $set_remember_me_cookie = null)
    {
        if (!empty($user_name) && !empty($user_password)) {
            $result = Authentication::validateAndGetUser($user_name, $user_password);
            if ($result) {
                if ($result->user_last_failed_login > 0) {
                    UserPDOWriter::resetFailedLoginsByUsername($result->user_name);
                }
                if ($set_remember_me_cookie) {
                    Authentication::setRememberMe($result->user_id);
                }
                Authentication::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_LOGIN_SUCCESSFUL'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
        return false;
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * TODO add throttling here ?
     *
     * @param string $cookie The cookie "remember_me"
     *
     * @return bool success state
     * @throws Exception
     */
    public static function loginWithCookie($cookie)
    {
        if ($cookie) {
            $result = self::validateCookieLogin($cookie);
            if ($result) {
                Authentication::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return false;
    }

    public static function validateCookieLogin($cookie)
    {
        // before list(), check it can be split into 3 strings.
        if (substr_count($cookie, ':') + 1 === 3) {
            // check cookie's contents, check if cookie contents belong together or token is empty
            [$user_id, $token, $hash] = explode(':', $cookie);
            $user_id = Encryption::decrypt($user_id);
            if (!empty($token) && !empty($user_id) && $hash === hash('sha256', $user_id . ':' . $token)) {
                return UserPDOReader::getByIdAndToken($user_id, $token);
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return false;
    }

    public static function loginWithFacebook($fbid)
    {
        if (!empty($fbid)) {
            $result = UserPDOReader::getByFbid($fbid);
            if ($result) {
                Authentication::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
        return false;
    }
}
