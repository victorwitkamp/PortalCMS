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
     * This is just a bulletproof version of Redirect::redirectPage(), redirecting to an ABSOLUTE URL path like
     * "http://www.mydomain.com/user/profile", useful as people had problems with the RELATIVE URL path generated
     * by Redirect::redirectPage() when using HUGE inside sub-folders.
     *
     * @param $path string
     */
    public static function toPreviousViewedPageAfterLogin($path)
    {
        header('location: http://'.$_SERVER['HTTP_HOST'].'/'.$path);
    }

        /**
     * To the homepage
     */
    public static function login()
    {
        // header("location: " . Config::get('URL'));
        self::redirectPage('login/login.php');
    }

    /**
     * To the homepage
     */
    public static function home()
    {
        // header("location: " . Config::get('URL'));
        self::redirectPage('home/index.php');
    }

    /**
     * To the homepage
     */
    public static function Error()
    {
        // header("location: " . Config::get('URL'));
        self::redirectPage('includes/Error.php');
    }

    /**
     * To the homepage
     */
    public static function permissionerror()
    {
        // header("location: " . Config::get('URL'));
        self::redirectPage('includes/permissionError.php');
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

    public static function redirectPage($url)
    {
        // if ($url!="") {
        //     echo '<script >window.location="'.Config::get('URL').$url.'";</script>';
        //     exit;
        // }
        header("location: ".Config::get('URL').$url);
        // exit();
    }
}
