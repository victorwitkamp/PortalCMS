<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Security\Authentication\Service\LogoutService;

/**
 * LogoutController
 */
class LogoutController
{
    /**
     */
    public function index() : bool
    {
        return LogoutService::logout();
    }
}
