<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Controllers;

use League\Plates\Engine;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Session\Session;

class Controller
{
    /**
     * @var View View The view object
     */
    // public $View;

    public function __construct()
    {
        Session::init();

        if (!Authentication::userIsLoggedIn() && !empty(Request::cookie('remember_me'))) {
            if (LoginController::loginWithCookie()) {
                Redirect::to('Home');
            } else {
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Login/indexNew');
            }
        }
    // $this->View = new View();
    }
}
