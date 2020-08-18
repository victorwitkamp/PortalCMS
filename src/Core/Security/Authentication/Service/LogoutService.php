<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authentication\Service;

use PortalCMS\Core\HTTP\Cookie;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

/**
 * Class LogoutService
 * @package PortalCMS\Core\Security\Authentication\Service
 */
class LogoutService
{
    /**
     */
    public static function logout(): bool
    {
        if (Authentication::userIsLoggedIn()) {
            $user_id = (int)Session::get('user_id');
            if (!empty($user_id)) {
                UserMapper::clearRememberMeToken($user_id);
                if (Cookie::delete()) {
                    Session::destroy();
                    Session::init();
                    Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
                    Redirect::to('Login');
                    return true;
                }
            }
        }
        Session::destroy();
        Session::init();
        Session::add('feedback_positive', Text::get('FEEDBACK_LOGOUT_INVALID'));
        Redirect::to('Login');
        return false;
    }
}
