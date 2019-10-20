<?php

namespace PortalCMS\Core;

use PortalCMS\User\UserMapper;

class Cookie
{
    public static function setSessionCookie()
    {
        // set session cookie setting manually,
        // Why? because you need to explicitly set session expiry, path, domain, secure, and HTTP.
        // @see https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Cookies
        setcookie(
            session_name(),
            session_id(),
            time() + Config::get('SESSION_RUNTIME'),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            // Config::get('COOKIE_SECURE'),
            $secure = true,
            Config::get('COOKIE_HTTP')
        );
    }

    public static function setRememberMe($token)
    {
        // set cookie, and make it available only for the domain created on (to avoid XSS attacks, where the
        // attacker could steal your remember-me cookie string and would login itself).
        // If you are using HTTPS, then you should set the "secure" flag (the second one from right) to true, too.
        // @see http://www.php.net/manual/en/function.setcookie.php
        setcookie(
            'remember_me',
            $token,
            time() + Config::get('COOKIE_RUNTIME'),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            // Config::get('COOKIE_SECURE'),
            $secure = true,
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
     * @return bool
     */
    public static function delete($user_id = null)
    {
        // is $user_id was set, then clear remember_me token in database
        if (isset($user_id)) {
            UserMapper::clearRememberMeToken($user_id);
        }
        // delete remember_me cookie in browser
        if (setcookie(
            'remember_me',
            false,
            time() - (3600 * 24 * 3650),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            Config::get('COOKIE_SECURE'),
            Config::get('COOKIE_HTTP')
        )
        ) {
            return true;
        }
        return false;
    }
}
