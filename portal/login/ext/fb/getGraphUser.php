<?php
declare(strict_types=1);

use Facebook\Exceptions\FacebookSDKException;
use PortalCMS\Core\Session\Session;

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
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
        Session::add('feedback_negative', 'Error getting long-lived access token: ' . $e->getMessage());
        exit;
    }
}

$_SESSION['fb_access_token'] = (string)$accessToken;

try {
    $response = $fb->get('/me?fields=id,name,email', $_SESSION['fb_access_token']);
} catch (FacebookSDKException $e) {
    Session::add('feedback_negative', 'Facebook SDK returned an error: ' . $e->getMessage());
    exit;
}

try {
    $facebookUser = $response->getGraphUser();
} catch (FacebookSDKException $e) {
    Session::add('feedback_negative', 'Facebook SDK returned an error: ' . $e->getMessage());
    exit;
}
