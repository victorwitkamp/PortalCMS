<?php

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
        //         Redirect::to("login/login.php");
        //     }
        // }

    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    public static function index()
    {
        if (!Login::isUserLoggedIn()) {
            // $data = array('redirect' => Request::get('redirect') ? Request::get('redirect') : NULL);
            // $this->View->render('login/index', $data);
            return Redirect::login();
        }
        return Redirect::home();
    }

    /**
     * The login action, when you do login/login
     */
    public static function loginWithPassword()
    {
        if (!Csrf::isTokenValid()) {
            Login::logout();
            return Redirect::login();
        }
        $login_successful = Login::loginWithPassword(
            Request::post('user_name'),
            Request::post('user_password'),
            Request::post('set_remember_me_cookie')
        );
        if ($login_successful) {
            if (Request::post('redirect')) {
                return Redirect::toPreviousViewedPageAfterLogin(ltrim(urldecode(Request::post('redirect')), '/'));
            }
            return Redirect::home();
        }
        if (Request::post('redirect')) {
            return Redirect::to('login/login.php?redirect='.ltrim(urlencode(Request::post('redirect')), '/'));
        }
        return Redirect::login();
    }

    /**
     * Login with cookie
     */
    public static function loginWithCookie()
    {
        $login_successful = Login::loginWithCookie(Request::cookie('remember_me'));
        if ($login_successful) {
            return Redirect::home();
        }
        // if not, delete cookie (outdated? attack?) and route user to login form to prevent infinite login loops
        Cookie::delete();
        return Redirect::login();
    }

    /**
     * Login with Facebook
     */
    public static function loginWithFacebook($fbid)
    {
        $login_successful = Login::loginWithFacebook($fbid);
        if ($login_successful) {
            if (Request::post('redirect')) {
                return Redirect::toPreviousViewedPageAfterLogin(ltrim(urldecode(Request::post('redirect')), '/'));
            }
            return Redirect::home();
        }
        if (Request::post('redirect')) {
            return Redirect::to('login/login.php?redirect='.ltrim(urlencode(Request::post('redirect')), '/'));
        }
        return Redirect::login();
    }

    /**
     * The logout action
     * Perform logout, redirect user to main-page
     */
    public static function logout()
    {
        Login::logout();
        Redirect::login();
    }

}