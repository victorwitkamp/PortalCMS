<?php
declare(strict_types=1);

use Facebook\Exceptions\FacebookSDKException;
use PortalCMS\Core\Config\Config;

try {
    $fb = new Facebook\Facebook(
        [
            'app_id' => Config::get('FB_APP_ID'),
            'app_secret' => Config::get('FB_APP_SECRET'),
            'default_graph_version' => 'v2.10'
        ]
    );
} catch (FacebookSDKException $e) {
    echo 'FacebookSDKException: ' . $e->getMessage();
    die;
}
