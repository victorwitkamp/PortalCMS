<?php

use PortalCMS\Core\Authentication\Service\LogoutService;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
// if (Authentication::isUserLoggedIn()) {
    LogoutService::logout();
// }
