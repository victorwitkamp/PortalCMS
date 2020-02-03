<?php
declare(strict_types=1);

use PortalCMS\Core\Application\Application;

if (file_exists(__DIR__ . '/../src/Init.php')) {
    include __DIR__ . '/../src/Init.php';
    new Application();
} else {
    echo 'Failed to initialize the application. Init.php does not exist.';
}
