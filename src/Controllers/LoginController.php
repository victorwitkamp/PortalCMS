<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authentication\Service\LoginService;
use PortalCMS\Core\Security\Authentication\Service\LogoutService;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

/**
 * LoginController
 * Controls everything that is authentication-related
 */
class LoginController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users (for sure not
     * needed in the LoginController).
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['loginSubmit'])) {
            self::loginWithPassword();
        }
        // if (isset($_POST['signupSubmit'])) {
        //     $this->signup($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
        // }
        // if (isset($_POST['activateSubmit'])) {
        //     if ($this->activate($_POST['email'], $_POST['code'])) {
        //         Redirect::to("login");
        //     }
        // }
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    public static function index()
    {
        if (Authentication::userIsLoggedIn()) {
            Redirect::to('home');
        } else {
            // $data = array('redirect' => Request::get('redirect') ? Request::get('redirect') : NULL);
            // $this->View->render('login/index', $data);
            self::loginWithCookie();
        }
    }

    /**
     * The login action, when you do login/login
     */
    public static function loginWithPassword()
    {
        if (!Csrf::isTokenValid()) {
            Session::add('feedback_negative', 'Invalid CSRF token.');
            Redirect::to('login');
            return void;
        }
        $login_successful = LoginService::loginWithPassword(
            Request::post('user_name'),
            Request::post('user_password'),
            Request::post('set_remember_me_cookie')
        );
        if (!$login_successful) {
            Redirect::to('login');
            return void;
        }
        $redirect = Request::post('redirect');
        if (!empty($redirect)) {
            Redirect::to($redirect);
            return void;
        }
        Redirect::to('home');
        return void;
    }

    /**
     * Login with cookie
     */
    public static function loginWithCookie()
    {
        if (LoginService::loginWithCookie(Request::cookie('remember_me'))) {
            Redirect::to('home');
        } else {
            // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
            Cookie::delete();
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Login/index');
        }
    }

    /**
     * Login with Facebook
     * @param $fbid
     */
    public static function loginWithFacebook(int $fbid)
    {
        if (LoginService::loginWithFacebook($fbid)) {
            // if (Request::post('redirect')) {
            //     return Redirect::to(ltrim(urldecode(Request::post('redirect')), '/'));
            // }
            Redirect::to('home');
        } else {
            // if (Request::post('redirect')) {
            //     return Redirect::to('login/?redirect='.ltrim(urlencode(Request::post('redirect')), '/'));
            // }
            Redirect::to('login');
        }
    }
}
