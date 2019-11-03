<?php
declare(strict_types=1);

namespace PortalCMS\Core\Controllers;

use PortalCMS\Core\View\View;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Core\Authentication\Authentication;

/**
 * This is the "base controller class". All other "real" controllers extend this class.
 * Whenever a controller is created, we also
 * 1. initialize a session
 * 2. check if the user is not logged in anymore (session timeout) but has a cookie
 */
class Controller
{
    /**
     * @var View View The view object
     */
    public $View;

    /**
     * Construct the (base) controller. This happens when a real controller is constructed, like in
     * the constructor of IndexController when it says: parent::__construct();
     */
    public function __construct()
    {
        // always initialize a session
        Session::init();

        // user is not logged in but has remember-me-cookie ? then try to login with cookie ("remember me" feature)
        if (!Authentication::userIsLoggedIn() && Request::cookie('remember_me')) {
            LoginController::loginWithCookie();
        }

        // create a view object to be able to use it inside a controller, like $this->View->render();
        $this->View = new View();
    }
}
