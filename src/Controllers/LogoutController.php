<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Security\Authentication\Service\LogoutService;
use Psr\Http\Message\ResponseInterface;

/**
 * LogoutController
 */
class LogoutController
{
    /**
     */
    public function index() : ResponseInterface
    {
        return LogoutService::logout();
    }
}
