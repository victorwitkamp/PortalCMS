<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Security\Authentication\Service;

use Exception;
use PortalCMS\Core\Security\Encryption;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\User\Password;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

/**
 * Class LoginValidator
 * @package PortalCMS\Core\Security\Authentication\Service
 */
class LoginValidator
{
    /**
     */
    public static function validateLogin(string $user_name, string $user_password): ?object
    {
        if (empty($user_name) || empty($user_password)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return null;
        } elseif (!self::checkSessionBruteForce()) {
            Session::add('feedback_negative', Text::get('FEEDBACK_BRUTE_FORCE_CHECK_FAILED'));
            return null;
        }
        return self::getUser($user_name, $user_password);
    }

    /**
     * Brute force attack mitigation
     * block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
     * @return bool Did the user passed the brute force validation?
     */
    public static function checkSessionBruteForce(): bool
    {
        if ((Session::get('failed-login-count') >= 3) && strtotime(Session::get('last-failed-login')) > (strtotime(date('Y-m-d H:i:s')) - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            return false;
        }
        return true;
    }

    /**
     */
    public static function getUser(string $user_name, string $user_password): ?object
    {
        $user = UserMapper::getByUsername($user_name);
        if (empty($user)) {
            self::incrementUserNotFoundCounter();
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return null;
        } elseif (self::checkUserBruteForce($user) === false) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return null;
        } elseif (self::verifyIsActive($user) === false) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return null;
        } elseif (Password::verifyPassword($user, $user_password) !== true) {
            UserMapper::setFailedLoginByUsername($user->user_name);
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return null;
        } else {
            self::resetUserNotFoundCounter();
            return $user;
        }
    }

    /**
     */
    public static function incrementUserNotFoundCounter(): bool
    {
        return Session::set('failed-login-count', Session::get('failed-login-count') + 1) && Session::set('last-failed-login', time());
    }

    /**
     * Brute force attack mitigation
     * @param object $user The user object
     * @return bool Did the user passed the brute force validation?
     */
    public static function checkUserBruteForce(object $user): bool
    {
        return !(($user->user_failed_logins >= 3) && strtotime($user->user_last_failed_login) > (strtotime(date('Y-m-d H:i:s')) - 30));
    }

    /**
     * @param object $user
     * @return bool
     */
    /**
     * @param object $user
     * @return bool
     */
    /**
     */
    public static function verifyIsActive(object $user): bool
    {
        return ($user->user_active === 1);
    }

    /**
     * @return bool
     */
    /**
     * @return bool
     */
    /**
     */
    public static function resetUserNotFoundCounter(): bool
    {
        return Session::set('failed-login-count', 0) && Session::set('last-failed-login', '');
    }

    /**
     * @param string $cookie
     * @return object|null
     */
    /**
     * @param string $cookie
     * @return object|null
     */
    /**
     */
    public static function validateCookieLogin(string $cookie): ?object
    {
        if (substr_count($cookie, ':') + 1 === 3) {
            [ $user_id, $token, $hash ] = explode(':', $cookie);
            try {
                $user_id = (int)Encryption::decrypt($user_id);
                if (!empty($token) && !empty($user_id) && ($hash === hash('sha256', $user_id . ':' . $token))) {
                    return new ValidatedCookie($user_id, $token);
                }
                Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            } catch (Exception $e) {
                Session::add('feedback_negative', 'Decryption of cookie failed.');
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        }
        return null;
    }
}
