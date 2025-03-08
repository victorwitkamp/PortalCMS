<?php

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/getGraphUser.php';

(new App\Controller\LoginController())->loginWithFacebook((int) $facebookUser['id']);
