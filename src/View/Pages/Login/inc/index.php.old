<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Popup;

require $_SERVER['DOCUMENT_ROOT'] . '/Login/ext/fb/config.php';
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(Config::get('FB_LOGIN_URL'), $permissions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?= SiteSetting::getStaticSiteSetting('site_name') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= SiteSetting::getStaticSiteSetting('site_theme') ?>/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <!-- <script src="/includes/js/avantui.js"></script> -->
    <script src="/dist/cookieconsent/build/cookieconsent.min.js" async></script>
    <script src="/includes/js/cookieconsent.init.js" async></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">
    <link rel="stylesheet" type="text/css" href="/includes/css/floating-labels.css">
    <link rel="stylesheet" type="text/css" href="/includes/css/newlogin.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
</head>
<body class='bg'>
    <?php require 'inc/loadingAnimation.php'; ?>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="<?= SiteSetting::getStaticSiteSetting('site_url') ?>"><?= SiteSetting::getStaticSiteSetting('site_name') ?></a>
    </nav>
    <main>
        <div class="alert-container"><?php Alert::renderFeedbackMessages(); ?></div>
        <div class="content">
        <div class="container-fluid">
            <section class="bglogin">
            <div class="user_options-container">
                <div class="user_options-text">
                <div class="user_options-unregistered">
                    <h2 class="user_unregistered-title">Nog geen account?</h2>
                    <p class="user_unregistered-text">Klik op Registreren en maak direct een account aan.</p>
                    <button class="btn btn-outline-info user_unregistered-signup" id="signup-button">Registreren</button>
                </div>
                <div class="user_options-registered">
                    <h2 class="user_registered-title">Heb je al een account?</h2>
                    <p class="user_registered-text">Log in met je bestaande gegevens.</p>
                    <button class="btn btn-outline-info user_registered-login" id="login-button">Login</button>
                </div>
                </div>
                <div class="user_options-forms" id="user_options-forms">
                    <?php require 'inc/Login.inc.php'; ?>
                    <?php require 'inc/registration.inc.php'; ?>
                </div>
            </div>
            </section>
            <script src="/includes/js/newlogin.js"></script>
            <?php Popup::show(); ?>
        </div>
        </div>
    </main>
</body>
</html>
