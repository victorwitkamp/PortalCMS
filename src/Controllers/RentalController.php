<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);
namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Router;

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
        Router::processRequests($this->requests, __CLASS__);
    }

    /**
     * Overview
     */
    public static function overview()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Rental/overview');
    }
}
