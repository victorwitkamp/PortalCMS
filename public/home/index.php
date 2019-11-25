<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
$templates = new League\Plates\Engine(DIR_VIEW);
echo $templates->render('Pages/Home/index');
