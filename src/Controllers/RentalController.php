<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);
namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authentication\Authentication;

class RentalController extends Controller
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
    public function overview()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Rental/overview');
    }
}
