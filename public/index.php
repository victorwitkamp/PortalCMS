<?php
// echo 'we are in index.php';
// die;
use PortalCMS\Core\Application\Application;

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/Init.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
    // LoginController::index();

    // start our application
    new Application();
} else {
    echo 'Configure Init.php first!';
}
