<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Application;

use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\HTTP\Request;

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
    private $controller_name;
    /** @var string Just the name of the controller's method, useful for checks inside the view ("where am I ?") */
    private $action_name;
    /**
     * Start the application, analyze URL elements, call according controller/method or relocate to fallback location
     */
    public function __construct()
    {
        $this->splitUrl();
        $this->createControllerAndActionNames();
        if (file_exists(DIR_CONTROLLERS . $this->controller_name . '.php')) {
            require DIR_CONTROLLERS . $this->controller_name . '.php';
            $name = "PortalCMS\\Controllers" . "\\" . $this->controller_name;
            $this->controller = new $name();
            if (method_exists($this->controller, $this->action_name)) {
                if (!empty($this->parameters)) {
                    call_user_func_array([$this->controller, $this->action_name], $this->parameters);
                } else {
                    $this->controller->{$this->action_name}();
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
            // split URL
            $url = trim(Request::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            // put URL parts into according properties
            $this->controller_name = isset($url[0]) ? $url[0] : null;
            $this->action_name = isset($url[1]) ? $url[1] : null;
            // remove controller name and action name from the split URL
            unset($url[0], $url[1]);
            // rebase array keys and store the URL parameters
            $this->parameters = array_values($url);
        }
    }

    private function createControllerAndActionNames()
    {
        if (!$this->controller_name) {
            $this->controller_name = Config::get('DEFAULT_CONTROLLER');
        }
        if (!$this->action_name || (strlen($this->action_name) === 0)) {
            $this->action_name = Config::get('DEFAULT_ACTION');
        }
        $this->controller_name = ucwords($this->controller_name) . 'Controller';
    }
}
