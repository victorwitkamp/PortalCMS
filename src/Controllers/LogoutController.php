<?php

/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Security\Authentication\Service\LogoutService;

/**
 * LogoutController
 */
class LogoutController extends Controller
{
    public function index()
    {
        LogoutService::logout();
    }
}
