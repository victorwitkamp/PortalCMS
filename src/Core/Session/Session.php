<?php

namespace PortalCMS\Core\Session;

use PortalCMS\Core\Filter\Filter;

/**
 * Session class
 *
 * handles the session stuff. creates session when no one exists, sets and gets values, and closes the session
 * properly (=logout). Not to forget the check if the user is logged in or not.
 */
class Session
{
    /**
     * starts the session
     */
    public static function init()
    {
        // if no session exist, start the session
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Sets a specific value to a specific key of the session
     *
     * @param mixed $key   key
     * @param mixed $value value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets/returns the value of a specific key of the session
     *
     * @param  mixed $key Usually a string, right ?
     * @return mixed the key's value or nothing
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            // filter the value for XSS vulnerabilities
            return Filter::XSSFilter($value);
        }
        return false;
    }

    /**
     * Adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static function add($key, $value)
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }

    /**
     * Deletes the session (= logs the user out)
     */
    public static function destroy()
    {
        if (session_destroy()) {
            return true;
        }
        return false;
    }
}
