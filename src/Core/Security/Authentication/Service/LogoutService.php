<?php


declare(strict_types=1);

namespace App\Core\Security\Authentication\Service;

use App\Core\HTTP\Cookie;
use App\Core\Security\Authentication\Authentication;
use App\Core\Session\Session;
use App\Core\User\UserMapper;
use App\Core\View\Text;

class LogoutService
{
    public function logout()
    {
        if (Authentication::userIsLoggedIn()) {
            $user_id = (int)Session::get('user_id');
            if (!empty($user_id)) {
                UserMapper::clearRememberMeToken($user_id);
                Cookie::delete();
            }
            if (Session::isActive()) {
                Session::destroy();
            }
            Session::init();
            $this->addFlash('success',Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
        } else {
            if (Session::isActive()) {
                Session::destroy();
            }
            Session::init();
            $this->addFlash('danger',Text::get('FEEDBACK_LOGOUT_INVALID'));
        }
    }
}
