<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

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
     *
     * @return void
     */
    public static function init() : void
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
     * @return void
     */
    public static function set($key, $value) : void
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
        return null;
    }

    /**
     * Adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public static function add($key, $value) : void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }

    /**
     * Deletes the session (= logs the user out)
     *
     * @return bool
     */
    public static function destroy() : bool
    {
        if (!session_destroy()) {
            self::add('feedback_warning', 'Your session has expired. Please log-in.');
            return false;
        }
        return true;
    }
}
