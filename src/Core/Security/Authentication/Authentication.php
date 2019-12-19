<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authentication;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

/**
 * Class Auth
 * Checks if user is logged in, if not then sends the user to "yourdomain.com/Login".
 */
class Authentication
{
    /**
     * The normal authentication flow, just check if the user is logged in (by looking into the session).
     * If user is not, then he will be redirected to login page and the application is hard-stopped via exit().
     */
    public static function checkAuthentication()
    {
        // initialize the session (if not initialized yet)
        Session::init();

        // if user is NOT logged in...
        // (if user IS logged in the application will not run the code below and therefore just go on)
        if (!self::userIsLoggedIn()) {

            // ... then treat user as "not logged in", destroy session, redirect to login page
            // Session::destroy();
            Session::add('feedback_negative', 'You need to log-in first.');


            // send the user to the login form page, but also add the current page's URI (the part after the base URL)
            // as a parameter argument, making it possible to send the user back to where he/she came from after a
            // successful login

            // header('location: ' . Config::get('URL') . 'Login/?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            // Redirect::to('Login/?redirect='.ltrim(urlencode($_SERVER['REQUEST_URI']), '/'));
            Redirect::to('Login/?redirect=' . urlencode($_SERVER['REQUEST_URI']));

            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            // this is not optimal and will be fixed in future releases
            exit();
        }

        // Hook to check is a cookie exists and if it matches a remember me token in the database.
        // if (!Cookie::isValid()) {

        // }
    }

    /**
     * Checks if the user is logged in or not
     *
     * @return bool user's login status
     */
    public static function userIsLoggedIn(): bool
    {
        return (Session::get('user_logged_in') ? true : false);
    }
}
