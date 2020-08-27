<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\HTTP;

use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\Application;
use PortalCMS\Core\Config\Config;
use function call_user_func;
use PortalCMS\Core\Exceptions\NotFoundException;

/**
 * Class Router
 * @package PortalCMS\Core\HTTP
 */
class Router
{
//    private $controller;
//    private $parameters = [];
//    private $controllerName;
//    private $actionName;

    private $request;
    private $response;

    private $routeMap = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $url, $callback)
    {
        $this->routeMap['get'][$url] = $callback;
    }

    public function post(string $url, $callback)
    {
        $this->routeMap['post'][$url] = $callback;
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;
        if (!$callback) {
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function renderViewOnly($view, $params = [])
    {
        return Application::$app->view->renderViewOnly($view, $params);
    }

//    public static function processRequests(array $requests, $class): void
//    {
//        foreach ($requests as $key => $value) {
//            if (($value === 'POST') && isset($_POST[$key])) {
//                call_user_func([$class, $key]);
//            }
//        }
//    }

//    private function splitUrl()
//    {
//        if (Request::get('url')) {
//            $url = trim(Request::get('url'), '/');
//            $url = filter_var($url, FILTER_SANITIZE_URL);
//            $url = explode('/', $url);
//            $this->controllerName = $url[0] ?? null;
//            $this->actionName = $url[1] ?? null;
//            unset($url[0], $url[1]);
//            $this->parameters = array_values($url);
//        }
//    }

//    private function setControllerAndAction()
//    {
//        if (empty($this->controllerName)) {
//            $this->controllerName = Config::get('DEFAULT_CONTROLLER');
//        }
//        if (empty($this->actionName)) {
//            $this->actionName = Config::get('DEFAULT_ACTION');
//        }
//        $this->controllerName = ucwords($this->controllerName) . 'Controller';
//    }

//    public function run()
//    {
//        $this->splitUrl();
//        $this->setControllerAndAction();
//        if (file_exists(DIR_CONTROLLERS . $this->controllerName . '.php')) {
//            require DIR_CONTROLLERS . $this->controllerName . '.php';
//            $name = "PortalCMS\\Controllers" . "\\" . $this->controllerName;
//            $this->controller = new $name();
//            if (method_exists($this->controller, $this->actionName)) {
//                if (!empty($this->parameters)) {
//                    call_user_func_array([$this->controller, $this->actionName], $this->parameters);
//                } else {
//                    $this->controller->{$this->actionName}();
//                }
//            } else {
//                $this->controller = new ErrorController();
//                $this->controller->notFound();
//            }
//        } else {
//            $this->controller = new ErrorController();
//            $this->controller->notFound();
//        }
//    }
}
