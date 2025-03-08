<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/getGraphUser.php';

(new App\Controller\AccountController())->setFbid(
    $_SESSION['user_id'],
    (int) $facebookUser['id']
);
