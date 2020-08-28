<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Plates\Engine;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use PortalCMS\Controllers\EventsController;
use PortalCMS\Controllers\HomeController;
use PortalCMS\Controllers\LoginController;
use PortalCMS\Controllers\LogoutController;
use PortalCMS\Core\HTTP\Session;

class Application
{
    public $router;
    public $request;
    public $session;

    public function __construct()
    {
        $container = new Container();
        $container->delegate(
            new ReflectionContainer()
        );
        $container->add(Engine::class)->addArgument(DIR_VIEW);

        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $this->router = new Router();
        $this->router->setStrategy($strategy);

        $this->request = ServerRequestFactory::fromGlobals();
        $this->session = new Session();

//        $this->router->group('/Account', function (RouteGroup $route) {};
//        $this->router->group('/Contracts', function (RouteGroup $route) {};
//        $this->router->group('/Email', function (RouteGroup $route) {};
//        $this->router->group('/Error', function (RouteGroup $route) {};

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

//        $this->router->group('/Invoices', function (RouteGroup $route) {});


        $this->router->group('/Login', function (RouteGroup $route) {
            $route->get('/', [ LoginController::class, 'index' ]);
            $route->post('/', [ LoginController::class, 'loginSubmit' ]);
        });

        $this->router->get('/Logout', [ LogoutController::class, 'index' ]);

//        $this->router->group('/Membership', function (RouteGroup $route) {};
//        $this->router->group('/Page', function (RouteGroup $route) {};
//        $this->router->group('/Profile', function (RouteGroup $route) {};
//        $this->router->group('/Settings', function (RouteGroup $route) {};
//        $this->router->group('/UserManagement', function (RouteGroup $route) {};

        (new SapiEmitter())->emit($this->router->dispatch($this->request));
    }
}
