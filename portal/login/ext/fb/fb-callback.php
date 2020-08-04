<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Controllers\AccountController;

require __DIR__ . '/../../../../src/Init.php';
require __DIR__ . '/config.php';
require __DIR__ . '/getGraphUser.php';

AccountController::setFbid($_SESSION['user_id'], (int)$facebookUser['id']);
