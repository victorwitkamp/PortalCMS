<?php

namespace PortalCMS\Authentication\Service;

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Cookie;
use PortalCMS\Core\Redirect;
use PortalCMS\Core\Session;
use PortalCMS\Core\Text;
use PortalCMS\User\UserMapper;
use PortalCMS\User\UserMapper;

/**
 * LogoutService
 */
class LogoutService
{
    /**
     * Log out process: delete cookie, delete session
     */
    public static function logout()
    {
        if (Authentication::userIsLoggedIn()) {
            $user_id = Session::get('user_id');
            if (!empty($user_id)) {
                UserMapper::clearRememberMeToken($user_id);
                if (Cookie::delete()) {
                    if (Session::destroy()) {
                        Session::init();
                        Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                        Redirect::login();
                    }
                }
            }
        } else {
            if (Session::destroy()) {
                Session::init();
                Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_INVALID'));
                Redirect::login();
            }
        }
    }
}
