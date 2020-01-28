<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Security\Authentication\Service;

use Exception;
use PortalCMS\Core\Security\Encryption;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\View\Text;

/**
 * Class LoginValidator
 * @package PortalCMS\Core\Security\Authentication\Service
 */
class LoginValidator
{
    /**
     * Brute force attack mitigation
     * block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
     * @return bool Did the user passed the brute force validation?
     */
    public static function checkBruteForce() : bool
    {
        if ((Session::get('failed-login-count') >= 3) && strtotime(Session::get('last-failed-login')) > (strtotime(date('Y-m-d H:i:s')) - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            return false;
        }
        return true;
    }

    /**
     * Brute force attack mitigation
     * @param object $result
     * @return bool Did the user passed the brute force validation?
     */
    public static function checkBruteForceByResult($result) : bool
    {
        if (($result->user_failed_logins >= 3) && strtotime($result->user_last_failed_login) > (strtotime(date('Y-m-d H:i:s')) - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return false;
        }
        return true;
    }

    /**
     * Validate the user, checks if password is correct.
     * If successful, user is returned
     * @param string $user_name user name
     * @param string $user_password user password
     * @return object|null
     */
    public static function validateAndGetUser(string $user_name, string $user_password) : ?object
    {
        if (!self::checkBruteForce()) {
            Session::add('feedback_negative', Text::get('FEEDBACK_BRUTE_FORCE_CHECK_FAILED'));
        } elseif (empty($user_name) || empty($user_password)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
        } else {
            return self::getUser($user_name, $user_password);
        }
        return null;
    }

    /**
     * @param string $user_name user name
     * @param string $user_password user password
     * @return object|null
     */
    public static function getUser(string $user_name, string $user_password) : ?object
    {
        $result = UserPDOReader::getByUsername($user_name);
        if (!empty($result) && self::checkBruteForceByResult($result) && self::verifyIsActive($result) && self::verifyPassword($result, $user_password)) {
            return $result;
        }
        self::incrementUserNotFoundCounter();
        Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
        return null;
    }

    /**
     * @param string $cookie The cookie
     * @return object|null
     */
    public static function validateCookieLogin(string $cookie) : ?object
    {
        if (substr_count($cookie, ':') + 1 !== 3) {
            Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
            return null;
        }

        [$user_id, $token, $hash] = explode(':', $cookie);
        try {
            $user_id = (int) Encryption::decrypt($user_id);
        } catch (Exception $e) {
            Session::add('feedback_negative', 'Decryption of cookie failed.');
            return null;
        }

        if (!empty($token) && !empty($user_id) && ($hash === hash('sha256', $user_id . ':' . $token))) {
            return new ValidatedCookie($user_id, $token);
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return null;
    }

    public static function verifyIsActive(object $result) : bool
    {
        if ($result->user_active !== 1) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return false;
        }
        self::resetUserNotFoundCounter();
        return true;
    }

    public static function verifyPassword(object $result, string $user_password) : bool
    {
        if (!password_verify(base64_encode($user_password), $result->user_password_hash)) {
            UserPDOWriter::setFailedLoginByUsername($result->user_name);
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }
        return true;
    }

    /**
     * Reset the failed-login-count to 0. Reset the last-failed-login to an empty string.
     */
    public static function resetUserNotFoundCounter()
    {
        Session::set('failed-login-count', 0);
        Session::set('last-failed-login', '');
    }

    /**
     * Increment the failed-login-count by 1. Add timestamp to last-failed-login.
     */
    public static function incrementUserNotFoundCounter()
    {
        Session::set('failed-login-count', Session::get('failed-login-count') + 1);
        Session::set('last-failed-login', time());
    }
}
