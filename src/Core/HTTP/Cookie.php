<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\User\UserPDOWriter;

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
            0,
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            // Config::get('COOKIE_SECURE'),
            true,
            Config::get('COOKIE_HTTP')
        );
    }

    public static function setRememberMe(string $token): bool
    {
        // set cookie, and make it available only for the domain created on (to avoid XSS attacks, where the
        // attacker could steal your remember-me cookie string and would login itself).
        // If you are using HTTPS, then you should set the "secure" flag (the second one from right) to true, too.
        // @see http://www.php.net/manual/en/function.setcookie.php
        if (setcookie(
            'remember_me',
            $token,
            time() + Config::get('COOKIE_RUNTIME'),
            Config::get('COOKIE_PATH'),
            Config::get('COOKIE_DOMAIN'),
            // Config::get('COOKIE_SECURE'),
            true,
            Config::get('COOKIE_HTTP')
        )) {
            return true;
        }
        return false;
    }

    /**
     * Deletes the cookie
     * Sets the remember-me-cookie to ten years ago (3600sec * 24 hours * 365 days * 10).
     * that's obviously the best practice to kill a cookie @see http://stackoverflow.com/a/686166/1114320
     */
    public static function delete(int $user_id = null): bool
    {
        if (isset($user_id)) {
            UserPDOWriter::clearRememberMeToken($user_id);
        }
        if (setcookie(
            'remember_me',
            '',
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
