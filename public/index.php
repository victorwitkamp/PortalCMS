<?php

use PortalCMS\Controllers\LoginController;

if (file_exists($_SERVER['DOCUMENT_ROOT']. '/Init.php')) {
    include $_SERVER['DOCUMENT_ROOT']. '/Init.php';
    LoginController::index();
} else {
    echo 'Configure Init.php first!';
}
