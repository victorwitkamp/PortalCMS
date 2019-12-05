<?php
use PortalCMS\Core\Application\Application;

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/Init.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
    new Application();
} else {
    echo 'Failed to initialize the application.';
}
