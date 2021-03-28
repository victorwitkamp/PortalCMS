<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Controllers;

use League\Plates\Engine;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Session\Session;

class Controller
{
    public function __construct()
    {
        Session::init();
        if (!Authentication::userIsLoggedIn()) {
            $cookie = Request::cookie('remember_me');
            if (!empty($cookie) && !LoginController::loginWithCookie($cookie)) {
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Login/indexNew');
            }
        }
    }
}
