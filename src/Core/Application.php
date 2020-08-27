<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core;

use PortalCMS\Controllers\HomeController;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\View\View;

class Application
{
    public static $app;
    public static $ROOT_DIR;
    public $layout = 'main';
    public $router;
    public $request;

    public $controller = null;
    public $session;
    public $view;

    public function __construct($rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
//        if ($this->request->isPost()) {
//            var_dump($this->request->getBody());
//            die;
//        }

        $this->router = new Router($this->request);
        $this->session = new Session();
        $this->view = new View();

        // $this->router->get('/Home', function($request) { return 'welkom'; });
        $this->router->get('/Home', [HomeController::class, 'index']);
        $this->router->post('/Login', [SiteController::class, 'loginSubmit']);
        $this->router->get('/Login', [LoginController::class, 'index']);
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            echo $this->router->renderView('_error', [
                'exception' => $e,
            ]);
        }
    }
}
