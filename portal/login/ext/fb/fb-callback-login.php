<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Controllers\LoginController;

require __DIR__ . '/../../../../src/Init.php';
require __DIR__ . '/config.php';
require __DIR__ . '/getGraphUser.php';

LoginController::loginWithFacebook((int) $facebookUser['id']);
