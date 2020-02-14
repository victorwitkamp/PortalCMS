<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Application;

use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\HTTP\Request;
use function call_user_func_array;

/**
 * Class Application
 * The heart of the application
 */
class Application
{
    /** @var mixed Instance of the controller */
    private $controller;
    /** @var array URL parameters, will be passed to used controller-method */
    private $parameters = [];
    /** @var string Just the name of the controller, useful for checks inside the view ("where am I ?") */
    private $controllerName;
    /** @var string Just the name of the controller's method, useful for checks inside the view ("where am I ?") */
    private $actionName;

    public function __construct()
    {
        $this->splitUrl();
        $this->setControllerAndAction();
        if (file_exists(DIR_CONTROLLERS . $this->controllerName . '.php')) {
            require DIR_CONTROLLERS . $this->controllerName . '.php';
            $name = "PortalCMS\\Controllers" . "\\" . $this->controllerName;
            $this->controller = new $name();
            if (method_exists($this->controller, $this->actionName)) {
                if (!empty($this->parameters)) {
                    call_user_func_array([$this->controller, $this->actionName], $this->parameters);
                } else {
                    $this->controller->{$this->actionName}();
                }
            } else {
                $this->controller = new ErrorController();
                $this->controller->notFound();
            }
        } else {
            $this->controller = new ErrorController();
            $this->controller->notFound();
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (Request::get('url')) {
            $url = trim(Request::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->controllerName = isset($url[0]) ? $url[0] : null;
            $this->actionName = isset($url[1]) ? $url[1] : null;
            unset($url[0], $url[1]);
            $this->parameters = array_values($url);
        }
    }

    private function setControllerAndAction()
    {
        if (empty($this->controllerName)) {
            $this->controllerName = Config::get('DEFAULT_CONTROLLER');
        }
        if (empty($this->actionName) || ($this->actionName === '')) {
            $this->actionName = Config::get('DEFAULT_ACTION');
        }
        $this->controllerName = ucwords($this->controllerName) . 'Controller';
    }
}
