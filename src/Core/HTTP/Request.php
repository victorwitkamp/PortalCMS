<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use function is_string;

/**
 * This is under development. Expect changes!
 * Class Request
 * Abstracts the access to $_GET, $_POST and $_COOKIE, preventing direct access to these super-globals.
 * This makes PHP code quality analyzer tools very happy.
 *
 * @see http://php.net/manual/en/reserved.variables.request.php
 */
class Request
{
    /**
     * Gets/returns the value of a specific key of the POST super-global.
     * When using just Request::post('x') it will return the raw and untouched $_POST['x'], when using it like
     * Request::post('x', true) then it will return a trimmed and stripped $_POST['x'] !
     *
     * @param  mixed $key   key
     * @param  bool  $clean marker for optional cleaning of the var
     * @return mixed the key's value or nothing
     */
    public static function post($key, bool $clean = false)
    {
        if (isset($_POST[$key])) {
            if (!empty($_POST[$key])) {
                if ($clean && is_string($key)) {
                    $return = trim(strip_tags($_POST[$key]));
                    if (!empty($return)) {
                        return $return;
                    }
                }
                return $_POST[$key];
            }
        }
        return null;
    }

    /**
     * gets/returns the value of a specific key of the GET super-global
     *
     * @param  mixed $key key
     * @return mixed the key's value or nothing
     */
    public static function get($key)
    {
        return $_GET[$key] ?? null;
    }

    /**
     * gets/returns the value of a specific key of the COOKIE super-global
     *
     * @param  mixed $key key
     * @return mixed the key's value or nothing
     */
    public static function cookie($key)
    {
        return $_COOKIE[$key] ?? null;
    }
}
