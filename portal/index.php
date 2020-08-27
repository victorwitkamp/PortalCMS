<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Application;

if (file_exists(__DIR__ . '/../src/Init.php')) {
    include __DIR__ . '/../src/Init.php';
    $app = new Application(dirname(__DIR__));
    $app->run();
} else {
    echo 'Failed to initialize the application. Init.php does not exist.';
}
