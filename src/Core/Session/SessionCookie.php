<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Session;

use PortalCMS\Core\Config\Config;

class SessionCookie
{
    public static function set() : bool
    {
        // @see https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Cookies
        $runtime = (int) Config::get('SESSION_RUNTIME');
        if ($runtime !== 0) {
            $runtime = time() + $runtime;
        }
        return setcookie(
            session_name(),
            session_id(),
            [
                'expires' => $runtime,
                'path' => (string) Config::get('COOKIE_PATH'),
                'domain' => (string) Config::get('COOKIE_DOMAIN'),
                'secure' => (bool) Config::get('COOKIE_SECURE'),
                'httponly' => (bool) Config::get('COOKIE_HTTP')
            ]
        );
    }
}
