<?php
declare(strict_types=1);

namespace PortalCMS\Core\Authentication\Service;

use Exception;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\User\UserPDOWriter;
use PortalCMS\Core\Encryption\Encryption;
use PortalCMS\Core\HTTP\Cookie;

class LoginService
{
    /**
     * Login process (for DEFAULT user accounts).
     *
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
            $result = self::validateAndGetUser($user_name, $user_password);
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
    public static function loginWithCookie($cookie) : bool
    {
        if ($cookie) {
            $result = self::validateCookieLogin($cookie);
            if (!empty($result)) {
                self::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return false;
    }

    public static function validateCookieLogin($cookie) : ?object
    {
        if (substr_count($cookie, ':') + 1 === 3) {
            [$user_id, $token, $hash] = explode(':', $cookie);
            $user_id = Encryption::decrypt($user_id);
            if (!empty($token) && !empty($user_id) && $hash === hash('sha256', $user_id . ':' . $token)) {
                $user = UserPDOReader::getByIdAndToken($user_id, $token);
                return $user;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_COOKIE_INVALID'));
        return null;
    }

    public static function loginWithFacebook($fbid) : bool
    {
        if (!empty($fbid)) {
            $result = UserPDOReader::getByFbid($fbid);
            if ($result) {
                self::setSuccessfulLoginIntoSession($result);
                Session::add('feedback_positive', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));
                return true;
            }
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
        return false;
    }

    /**
     * Brute force attack mitigation
     * block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
     *
     * @return bool
     */
    public static function checkBruteForce() : bool
    {
        if ((Session::get('failed-login-count') >= 3) && Session::get('last-failed-login') > (time() - 30)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            return false;
        }
        return true;
    }

    /**
     * Brute force attack mitigation
     *
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
     *
     * @param $user_name
     * @param $user_password
     *
     * @return mixed
     */
    public static function validateAndGetUser($user_name, $user_password)
    {
        if (self::checkBruteForce()) {
            $result = UserPDOReader::getByUsername($user_name);

            if (!empty($result)) {
                if (self::checkBruteForceByResult($result)) {
                    if (self::verifyPassword($result, $user_password)) {
                        if (self::verifyIsActive($result)) {
                            return $result;
                        }
                        return null;
                    }
                    return null;
                }
                return null;
            }
            self::incrementUserNotFoundCounter();
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return null;
        }
        return null;
    }

    public static function verifyIsActive($result)
    {
        if ($result->user_active === '1') {
            self::resetUserNotFoundCounter();
            return true;
        }
        Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
        return false;
    }

    public static function verifyPassword($result, $user_password)
    {
        if (password_verify(base64_encode($user_password), $result->user_password_hash)) {
            return true;
        }
        UserPDOWriter::setFailedLoginByUsername($result->user_name);
        Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
        return false;
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
