<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Security\Authentication\Authentication;

/**
 * Class HomeController
 * @package PortalCMS\Controllers
 */
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
    }

    public function index()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Home/Index');
    }
}
