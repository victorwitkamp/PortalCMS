<?php

use PortalCMS\Core\Config;

$fb = new Facebook\Facebook(
    [
    'app_id' => Config::get('FB_APP_ID'),
    'app_secret' => Config::get('FB_APP_SECRET'),
    'default_graph_version' => 'v2.10',
    ]
);
