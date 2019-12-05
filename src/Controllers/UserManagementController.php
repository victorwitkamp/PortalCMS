<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

class UserManagementController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    public function users()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('user-management');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/UserManagement/Users/index');
    }

    public function profile()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('user-management');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/UserManagement/Profile/index');
    }

    public function roles()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('role-management');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/UserManagement/Roles/index');
    }

    public function role()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('user-management');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/UserManagement/Role/index');
    }
}
