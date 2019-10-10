<?php
/**
 * Class Redirect
 *
 * Simple abstraction for redirecting the user to a certain page
 */
class Redirect
{
    /**
     * To the last visited page before user logged in (useful when people are on a certain page inside your application
     * and then want to log in (to edit or comment something for example) and don't to be redirected to the main page).
     *
     * This is just a bulletproof version of Redirect::to(), redirecting to an ABSOLUTE URL path like
     * "http://www.mydomain.com/user/profile", useful as people had problems with the RELATIVE URL path generated
     * by Redirect::to() when using HUGE inside sub-folders.
     *
     * @param $path string
     */
    public static function toPreviousViewedPageAfterLogin($path)
    {
        // header('location: https://'.$_SERVER['HTTP_HOST'].'/'.$path);

        header('location: '.Config::get('URL').$path);
    }

    public static function login()
    {
        self::to('login/login.php');
    }

    public static function home()
    {
        self::to('home/index.php');
    }

    public static function myAccount()
    {
        self::to('my-account/index.php');
    }

    public static function contracts()
    {
        self::to('rental/contracts/index.php');
    }
    public static function invoices()
    {
        self::to('rental/invoices/index.php');
    }
    public static function error()
    {
        self::to('includes/Error.php');
    }
        public static function preError()
        {
        self::to('login/error.php');
    }

    public static function permissionError()
    {
        self::to('includes/permissionError.php');
    }

    public static function mail()
    {
        self::to('mail/index.php');
    }

    /**
     * To the defined page, uses a relative path (like "user/profile")
     *
     * Redirects to a RELATIVE path, like "user/profile" (which works very fine unless you are using HUGE inside tricky
     * sub-folder structures)
     *
     * @see https://github.com/panique/huge/issues/770
     * @see https://github.com/panique/huge/issues/754
     *
     * @param $path string
     */
    public static function to($path)
    {
        header("location: ".Config::get('URL').$path);
    }
}
