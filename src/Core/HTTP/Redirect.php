<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use PortalCMS\Core\Config\Config;

/**
 * Class Redirect
 * Simple abstraction for redirecting the user to a certain page
 */
class Redirect
{
    /**
     * To the defined page, uses a relative path (like "user/profile")
     * Redirects to a RELATIVE path, like "user/profile"
     * @param $path
     */
    public static function to(string $url, bool $permanent = false)
    {
        // session_write_close();
        if (headers_sent() === false) {
            header('Location: ' . Config::get('URL') . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }
}
