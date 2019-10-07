<?php

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
        session_write_close();
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

    /**
     * update session id in database
     *
     * @access public
     * @static static method
     * @param  string $userId
     * @param  string $sessionId
     */
    public static function updateSessionId($userId, $sessionId = NULL)
    {
        $sql = "UPDATE users SET session_id = :session_id WHERE user_id = :user_id";

        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(array(':session_id' => $sessionId, ":user_id" => $userId));
    }

    /**
     * Checks if the user is logged in or not
     *
     * @return bool user's login status
     */
    public static function userIsLoggedIn()
    {
        return (self::get('user_logged_in') ? true : false);
    }
}
