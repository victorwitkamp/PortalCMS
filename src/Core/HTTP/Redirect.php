<?php
/**
 * Copyright Victor Witkamp (c) 2019.
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
    public static function to(string $path)
    {
        session_write_close();
        header('location: ' . Config::get('URL') . ltrim($path, '/'));
    }
}
