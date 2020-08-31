<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authentication\Service;

use Exception;
use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\HTTP\SessionCookie;
use PortalCMS\Core\Security\Encryption;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

/**
 * Class LoginService
 * @package PortalCMS\Core\Security\Authentication\Service
 */
class LoginService
{
    /**
     */
    public static function loginWithPassword(string $username, string $password, bool $rememberMe = false): bool
    {
        $user = LoginValidator::validateLogin($username, $password);
        if (!empty($user)) {
            if ($user->user_last_failed_login > 0) {
                UserMapper::resetFailedLoginsByUsername($user->user_name);
            }
            if ($rememberMe) {
                self::setRememberMe($user->user_id);
                Session::add('feedback_positive', Text::get('FEEDBACK_REMEMBER_ME_ENABLED'));
            }
            self::setSuccessfulLoginIntoSession($user);
            Activity::add('LoginWithPassword', $user->user_id);
            Session::add('feedback_positive', Text::get('FEEDBACK_LOGIN_SUCCESSFUL'));
            return true;
        }
        // Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
        return false;
    }

    /**
     */
    public static function setRememberMe(int $user_id): bool
    {
        // generate 64 char random string
        $token = hash('sha256', (string)mt_rand());

        UserMapper::updateRememberMeToken($user_id, $token);

        // generate cookie string that consists of user id, random string and combined hash of both
        // never expose the original user id, instead, encrypt it.
        try {
            $cookie_string_first_part = Encryption::encrypt((string)$user_id) . ':' . $token;
        } catch (Exception $e) {
            return false;
        }
        $cookie_string_hash = hash('sha256', $user_id . ':' . $token);
        $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

        return Cookie::setRememberMe($cookie_string);
    }

    /**
     */
    public static function setSuccessfulLoginIntoSession(object $user): bool
    {
        Session::init();
        session_regenerate_id(true);
        Session::set('user_id', $user->user_id);
        Session::set('user_name', $user->user_name);
        Session::set('user_email', $user->user_email);
        Session::set('user_fbid', $user->user_fbid);
        Session::set('user_logged_in', true);
        UserMapper::updateSessionId($user->user_id, session_id());
        UserMapper::saveTimestampByUsername($user->user_name);
        if (SessionCookie::set()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $cookie
     * @return bool
     */
    /**
     * @param string $cookie
     * @return bool
     */
    /**
     */
    public static function loginWithCookie(string $cookie): bool
    {
        if (!empty($cookie)) {
            $cookieResponse = LoginValidator::validateCookieLogin($cookie);
            if (!empty($cookieResponse->user_id) && (!empty($cookieResponse->token))) {
                $user = UserMapper::getByIdAndToken($cookieResponse->user_id, $cookieResponse->token);
                if (!empty($user)) {
                    self::setSuccessfulLoginIntoSession($user);
                    Activity::add('LoginWithCookie', $user->user_id);
                    Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param int $fbid
     * @return bool
     */
    /**
     * @param int $fbid
     * @return bool
     */
    /**
     */
    public static function loginWithFacebook(int $fbid): bool
    {
        if (!empty($fbid)) {
            $user = UserMapper::getByFbid($fbid);
            if (!empty($user)) {
                self::setSuccessfulLoginIntoSession($user);
                Activity::add('LoginWithFacebook', $user->user_id);
                Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
        return false;
    }
}
