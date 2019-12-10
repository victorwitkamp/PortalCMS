<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Security\Authentication\Authentication;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
    }

    public static function index()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Home/index');
    }
}
