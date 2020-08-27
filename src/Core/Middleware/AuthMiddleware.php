<?php
/**
 * User: TheCodeholic
 * Date: 7/25/2020
 * Time: 11:33 AM
 */

namespace thecodeholic\phpmvc\middlewares;

use thecodeholic\phpmvc\Application;
use thecodeholic\phpmvc\exception\ForbiddenException;

/**
 * Class AuthMiddleware
 */
class AuthMiddleware implements BaseMiddleware
{
    protected $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
