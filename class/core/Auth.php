<?php
/**
 * Class Auth
 * Checks if user is logged in, if not then sends the user to "yourdomain.com/login".
 * Auth::checkAuthentication() can be used in the constructor of a controller (to make the
 * entire controller only visible for logged-in users) or inside a controller-method to make only this part of the
 * application available for logged-in users.
 */
class Auth
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
        if (!Session::userIsLoggedIn()) {

            // ... then treat user as "not logged in", destroy session, redirect to login page
            Session::destroy();

            // send the user to the login form page, but also add the current page's URI (the part after the base URL)
            // as a parameter argument, making it possible to send the user back to where he/she came from after a
            // successful login

            // header('location: ' . Config::get('URL') . 'login/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            Redirect::to('login/login.php?redirect='.ltrim(urlencode($_SERVER['REQUEST_URI']), '/'));

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
     * Check whether the user that is currently logged on has a specific permission
     *
     * @param string $perm_desc
     *
     * @return bool
     */
    public static function checkPrivilege($perm_desc)
    {
        $Roles = User::getRoles(Session::get('user_id'));
        foreach ($Roles as $Role) {
            if (RolePermission::isAssigned($Role['role_id'], $perm_desc)) {
                return true;
            }
        }
        return false;
    }

}
