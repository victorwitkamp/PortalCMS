<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);
namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;

class ProfileController extends Controller
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
        Authentication::checkAuthentication();
        Router::processRequests($this->requests, __CLASS__);
    }

    /**
     * Overview
     */
    public function index()
    {
        $templates = new Engine(DIR_VIEW);
        $user = UserPDOReader::getProfileById((int) Request::get('id'));
        if (!empty($user)) {
            echo $templates->render('Pages/Profile/index', (array) $user);
        } else {
            header('HTTP/1.0 404 Not Found', true, 404);
            echo $templates->render('Pages/Error/Error', ['title' => '404 - Not found', 'message' => 'The requested page cannot be found']);
        }
    }
}
