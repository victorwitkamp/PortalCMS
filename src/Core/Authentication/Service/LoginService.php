<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Authentication\Service;

use Exception;
use PortalCMS\Core\Encryption\Encryption;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\View\Text;

class LoginService
{
    /**
     * Login process
     * @param string $user_name The user's name
     * @param string $user_password The user's password
     * @param mixed $set_remember_me_cookie Marker for usage of remember-me cookie feature
     *
     * @return bool
     * @throws Exception
     */
    public static function loginWithPassword($user_name, $user_password, $set_remember_me_cookie = null) : bool
    {
        if (!empty($user_name) && !empty($user_password)) {
            $result = LoginValidator::validateAndGetUser($user_name, $user_password);
            if (!empty($result)) {
                if ($result->user_last_failed_login > 0) {
                    UserPDOWriter::resetFailedLoginsByUsername($result->user_name);
                }
                if ($set_remember_me_cookie) {
                    self::setRememberMe($result->user_id);
                }
                self::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_LOGIN_SUCCESSFUL'));
                return true;
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
        }
        return false;
    }

    /**
     * performs the login via cookie (for DEFAULT user account, FACEBOOK-accounts are handled differently)
     * @param string $cookie The cookie "remember_me"
     * @return bool success state
     * @throws Exception
     */
    public static function loginWithCookie($cookie) : bool
    {
        if (!empty($cookie)) {
            $cookieResponse = LoginValidator::validateCookieLogin($cookie);
            if (!empty($cookieResponse->user_id) && (!empty($cookieResponse->token))) {
                $user = UserPDOReader::getByIdAndToken($cookieResponse->user_id, $cookieResponse->token);
                if (!empty($user)) {
                    self::setSuccessfulLoginIntoSession($user);
                    Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
                    return true;
                }
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return false;
    }

    public static function loginWithFacebook(int $fbid) : bool
    {
        if (!empty($fbid)) {
            // Todo: add more validation
            // If validated
            $user = UserPDOReader::getByFbid($fbid);
            if (!empty($user)) {
                self::setSuccessfulLoginIntoSession($user);
                Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
        return false;
    }

    /**
     * The real login process: The user's data is written into the session.
     * Cheesy name, maybe rename. Also maybe refactoring this, using an array.
     * @param $user
     */
    public static function setSuccessfulLoginIntoSession($user)
    {
        Session::init();

        // remove old and regenerate session ID. It's important to regenerate session on sensitive actions,
        // and to avoid fixated session. e.g. when a user logs in
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
     * @param $user_id
     * @return bool
     */
    public static function setRememberMe($user_id) : bool
    {
        // generate 64 char random string
        $token = hash('sha256', (string) mt_rand());

        UserPDOWriter::updateRememberMeToken($user_id, $token);

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        try {
            $cookie_string_first_part = Encryption::encrypt((string) $user_id) . ':' . $token;
        } catch (Exception $e) {
            return false;
        }
        $cookie_string_hash       = hash('sha256', $user_id . ':' . $token);
        $cookie_string            = $cookie_string_first_part . ':' . $cookie_string_hash;

        return Cookie::setRememberMe($cookie_string);
    }
}
