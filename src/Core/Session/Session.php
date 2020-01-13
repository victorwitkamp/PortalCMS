<?php
/**
 * Copyright Victor Witkamp (c) 2019.
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
    /**
     * starts the session
     *
     * @return void
     */
    public static function init(): void
    {
        // if no session exist, start the session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Sets a specific value to a specific key of the session
     *
     * @param mixed $key key
     * @param mixed $value value
     * @return void
     */
    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets/returns the value of a specific key of the session
     *
     * @param mixed $key Usually a string, right ?
     * @return mixed the key's value or nothing
     */
    public static function get($key, $filter = true)
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

    /**
     * Deletes the session (= logs the user out)
     *
     * @return bool
     */
    public static function destroy(): bool
    {
        if (!session_destroy()) {
            self::add('feedback_warning', 'Session could not be destroyed.');
            return false;
        }
        self::add('feedback_warning', 'Session destroyed.');
        return true;
    }

    /**
     * Adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public static function add($key, $value): void
    {
        $_SESSION[$key][] = $value;
        // session_write_close();
    }
}
