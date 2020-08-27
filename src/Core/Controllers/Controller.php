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
use PortalCMS\Core\HTTP\Session;

use PortalCMS\Core\Middleware\BaseMiddleware;
/**
 * Class Controller
 * @package PortalCMS\Core\Controllers
 */
class Controller
{
    public $layout = 'main';
    public $action = '';

    /**
     * @var \thecodeholic\phpmvc\BaseMiddleware[]
     */
    protected $middlewares = [];

//    public function __construct()
//    {
////        Session::init();
//
//        if (!Authentication::userIsLoggedIn() && !empty(Request::cookie('remember_me')) && !LoginController::loginWithCookie()) {
//            $templates = new Engine(DIR_VIEW);
//            echo $templates->render('Pages/Login/indexNew');
//        }
//    }

    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    public function render($view, $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return \thecodeholic\phpmvc\middlewares\BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
