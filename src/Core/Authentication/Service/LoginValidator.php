<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Authentication\Service;

use PortalCMS\Core\Encryption\Encryption;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\View\Text;

class LoginValidator
{
    /**
     * Brute force attack mitigation
     * block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
     * @return bool
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
     * @return bool
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
     * Validates the inputs of the users, checks if password is correct etc.
     * If successful, user is returned
     * @param $user_name
     * @param $user_password
     * @return object
     */
    public static function validateAndGetUser($user_name, $user_password) : ?object
    {
        if (self::checkBruteForce()) {
            if (empty($user_name) || !empty($user_password)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
                return null;
            }
            $result = UserPDOReader::getByUsername($user_name);
            if (!empty($result) && self::checkBruteForceByResult($result) && self::verifyIsActive($result) && self::verifyPassword($result, $user_password)) {
                return $result;
            }
            self::incrementUserNotFoundCounter();
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
        }
        return null;
    }

    public static function validateCookieLogin($cookie) : ?object
    {
        if (substr_count($cookie, ':') + 1 === 3) {
            [$user_id, $token, $hash] = explode(':', $cookie);
            $user_id = Encryption::decrypt($user_id);
            if (!empty($token) && !empty($user_id) && $hash === hash('sha256', $user_id . ':' . $token)) {
                $validatedCookie = new ValidatedCookie($user_id, $token);
                return $validatedCookie;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return null;
    }

    public static function verifyIsActive($result) : bool
    {
        if ($result->user_active === 1) {
            self::resetUserNotFoundCounter();
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
        return false;
    }

    public static function verifyPassword($result, $user_password) : bool
    {
        if (password_verify(base64_encode($user_password), $result->user_password_hash)) {
            return true;
        }
        UserPDOWriter::setFailedLoginByUsername($result->user_name);
        Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
        return false;
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