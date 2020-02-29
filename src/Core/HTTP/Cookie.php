<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use PortalCMS\Core\Config\Config;

class Cookie
{
    public static function setRememberMe(string $token): bool
    {
        $runtime = time() + (int) Config::get('COOKIE_RUNTIME');
        return setcookie(
            'remember_me',
            $token,
            [
                'expires' => $runtime,
                'path' => (string) Config::get('COOKIE_PATH'),
                'domain' => (string) Config::get('COOKIE_DOMAIN'),
                'secure' => (bool) Config::get('COOKIE_SECURE'),
                'httponly' => (bool) Config::get('COOKIE_HTTP')
            ]
        );
    }

    /**
     * Deletes the cookie
     * Sets the remember-me-cookie to ten years ago (3600sec * 24 hours * 365 days * 10).
     * that's obviously the best practice to kill a cookie @see http://stackoverflow.com/a/686166/1114320
     */
    public static function delete(): bool
    {
        return setcookie(
            'remember_me',
            '',
            [
                'expires' => (time() - (3600 * 24 * 3650)),
                'path' => (string) Config::get('COOKIE_PATH'),
                'domain' => (string) Config::get('COOKIE_DOMAIN'),
                'secure' => (bool) Config::get('COOKIE_SECURE'),
                'httponly' => (bool) Config::get('COOKIE_HTTP')
            ]
        );
    }
}
