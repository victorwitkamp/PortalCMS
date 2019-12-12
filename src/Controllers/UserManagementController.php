<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

class UserManagementController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'deleteuser' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function users()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Users/index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function profile()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Profile/index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function roles()
    {
        if (Authorization::hasPermission('role-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Roles/index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function role()
    {
        if (Authorization::hasPermission('user-management')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/UserManagement/Role/index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public static function deleteuser() {
        // Not inplemented yet
        $controller = new ErrorController();
        $controller->notFound();
    }
}
