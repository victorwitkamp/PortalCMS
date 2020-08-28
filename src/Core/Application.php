<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\RouteGroup;
use League\Route\Router;
use PortalCMS\Controllers\EventsController;
use PortalCMS\Controllers\HomeController;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Controllers\LogoutController;
use PortalCMS\Core\HTTP\Session;

class Application
{
    public static $app;
    public static $ROOT_DIR;
    public $router;
    public $request;
    public $session;

    public function __construct($rootDir)
    {
        self::$ROOT_DIR = $rootDir . '/../src/';
        self::$app = $this;
        $this->request = ServerRequestFactory::fromGlobals();
        $this->router = new Router();
        $this->session = new Session();

        $this->router->group('/Login', function (RouteGroup $route) {
            $route->get('/', [ LoginController::class, 'index' ]);
            $route->post('/', [ LoginController::class, 'loginSubmit' ]);
        });
        $this->router->group('/Events', function (RouteGroup $route) {
            $route->get('/', [ EventsController::class, 'index' ]);
            $route->get('/Details', [ EventsController::class, 'details' ]);
            $route->get('/Add', [ EventsController::class, 'add' ]);
            $route->post('/Add', [ EventsController::class, 'addEvent' ]);
            $route->get('/Edit', [ EventsController::class, 'edit' ]);
            $route->post('/Edit', [ EventsController::class, 'updateEvent' ]);
            $route->post('/Delete', [ EventsController::class, 'deleteEvent' ]);
            $route->get('/loadCalendarEvents', [ EventsController::class, 'loadCalendarEvents' ]);
            $route->post('/UpdateEventDate', [ EventsController::class, 'updateEventDate' ]);
        });
        $this->router->get('/Home', [ HomeController::class, 'index' ]);
        $this->router->get('/Logout', [ LogoutController::class, 'index' ]);
        (new SapiEmitter())->emit($this->router->dispatch($this->request));
    }
}
