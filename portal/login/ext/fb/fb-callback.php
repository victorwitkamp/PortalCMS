<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use Facebook\Exceptions\FacebookSDKException;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\User;

require __DIR__ . '/../../../../src/Init.php';
require __DIR__ . '/config.php';

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
    $accessToken = $helper->getAccessToken();
} catch (FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo 'Error: ' . $helper->getError() . "\n";
        echo 'Error Code: ' . $helper->getErrorCode() . "\n";
        echo 'Error Reason: ' . $helper->getErrorReason() . "\n";
        echo 'Error Description: ' . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

$oAuth2Client = $fb->getOAuth2Client();

if (!$accessToken->isLongLived()) {
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (FacebookSDKException $e) {
        echo 'Error getting long-lived access token: ' . $e->getMessage() . "\n\n";
        exit;
    }
}

$_SESSION['fb_access_token'] = (string) $accessToken;

try {
    $response = $fb->get('/me?fields=id,name,email', $_SESSION['fb_access_token']);
} catch (FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

try {
    $facebookUser = $response->getGraphUser();
} catch (FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
echo((int) $facebookUser['id']);

User::setFbid(
    (int) $facebookUser['id']
);
