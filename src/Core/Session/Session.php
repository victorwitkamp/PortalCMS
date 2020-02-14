<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Session;

use PortalCMS\Core\Security\Filter;

/**
 * Session class
 *
 * handles the session stuff. creates session when no one exists, sets and gets values, and closes the session
 * properly (=logout). Not to forget the check if the user is logged in or not.
 */
class Session
{
    public static function init() : void
    {
        // if no session exist, start the session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function set($key, $value) : bool
    {
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * Gets/returns the value of a specific key of the session
     *
     * @param  mixed $key Usually a string, right ?
     * @return mixed the key's value or nothing
     */
    public static function get($key, bool $filter = true)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            // filter the value for XSS vulnerabilities
            if ($filter) {
                return Filter::XSSFilter($value);
            }
            return $value;
        }
        return null;
    }

    public static function add($key, $value) : void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }

    public static function destroy() : bool
    {
        if (!session_destroy()) {
            self::add('feedback_warning', 'Session could not be destroyed.');
            return false;
        }
        self::add('feedback_warning', 'Session destroyed.');
        return true;
    }
}
