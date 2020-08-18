<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use function is_string;

/**
 * Request Class
 * Abstracts the access to $_GET, $_POST and $_COOKIE, preventing direct access to these super-globals.
 */
class Request
{
    /**
     * When using just Request::post('x') it will return the raw and untouched $_POST['x'], when using it like
     * Request::post('x', true) will return a trimmed and stripped $_POST['x'] !
     * @return mixed|string|null
     */
    public static function post($key, bool $clean = false)
    {
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            if ($clean && is_string($key)) {
                $return = trim(strip_tags($_POST[$key]));

            } else {
                $return = $_POST[$key];
            }
            if (!empty($return)) {
                return $return;
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public static function get($key)
    {
        return $_GET[$key] ?? null;
    }

    /**
     * @return mixed|null
     */
    public static function cookie($key)
    {
        return $_COOKIE[$key] ?? null;
    }
}
