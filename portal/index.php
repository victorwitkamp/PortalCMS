<?php
use PortalCMS\Core\Application\Application;

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../src/Init.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/../src/Init.php';
    new Application();
} else {
    echo 'Failed to initialize the application. Init.php does not exist.';
}
