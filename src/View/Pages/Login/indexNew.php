<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\View;

require $_SERVER['DOCUMENT_ROOT'] . '/login/ext/fb/config.php';
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(Config::get('FB_LOGIN_URL'), $permissions);
?>

<!DOCTYPE html>
<html lang="en" ng-app="app" class="ng-scope">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - <?= SiteSetting::getStaticSiteSetting('site_name') ?></title>
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= SiteSetting::getStaticSiteSetting('site_theme') ?>/bootstrap.min.css">

    <link rel="stylesheet" href="/includes/LoginNewStyle.css" />
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="/dist/cookieconsent/build/cookieconsent.min.js" async></script>
    <script src="/includes/js/cookieconsent.init.js" async></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

</head>

<body>
    <?php require 'inc/loadingAnimation.php'; ?>

    <div class="container-fluid container-auth">
        <div class="auth-brand m-t-md m-b-md"></div>
    </div>
    <form method="POST" novalidate="" class="ng-valid ng-valid-email ng-dirty ng-valid-parse">
        <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken() ?>" />
        <?php if (!empty(Request::get('redirect'))) { ?><input type="hidden" name="redirect" value="<?= View::encodeHTML(Request::get('redirect')) ?>" /><?php } ?>
        <div class="container-fluid container-auth">
            <div class="panel panel-auth">
                <div class="panel-heading">
                    <h2 id="title-container" class="panel-title text-center"><?= Text::get('LABEL_LOG_IN') ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></h2>
                    <?php Alert::renderFeedbackMessages(); ?>
                </div>
                <div class="panel-body">
                    <div class="form-group required float in" input-group="">
                        <input type="text" class="form-control ng-valid ng-valid-email ng-not-empty ng-dirty ng-valid-parse ng-touched" ng-initial="" id="email" name="user_name" ng-model="user.name" placeholder="Gebruikersnaam" value="" tabindex="1" autocomplete="username" validation="required" autofocus="" />
                        <label for="text" class="label-float">Gebruikersnaam</label>
                        <i class="input-icon fa fa-fw fa-spin fa-circle-o-notch"></i>
                    </div>
                    <div class="form-group required float in" input-group="">
                        <input type="password" class="form-control ng-valid ng-not-empty ng-dirty ng-valid-parse ng-touched" ng-initial="" id="password" name="user_password" ng-model="password" placeholder="Wachtwoord" tabindex="2" autocomplete="current-password" validation="required" />
                        <label for="password" class="label-float">Wachtwoord</label>
                        <i class="input-icon fa fa-fw fa-spin fa-circle-o-notch"></i>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="rememberMe" name="set_remember_me_cookie" class="form-check-input">
                        <label class="form-check-label" for="rememberMe"><?= Text::get('LABEL_REMEMBER_ME') ?></label>
                    </div>
                    <hr />
                    <input type="submit" name="loginSubmit" class="btn btn-primary" tabindex="3" value="<?= Text::get('LABEL_LOG_IN') ?>" />
                    <a href="<?= $loginUrl ?>" class="btn btn-info"><i class="fab fa-facebook"></i> <?= Text::get('LABEL_CONTINUE_WITH_FACEBOOK') ?></a>
                </div>
                <div class="panel-footer">
                    <!-- <div class="checkbox checkbox-right m-y-0 checkbox-switch" input-group=""> -->
                    <label>
                        <!-- <input type="checkbox" name="remember" ng-model="remember" ng-initial="" class="ng-untouched ng-valid ng-dirty ng-valid-parse ng-empty"> -->
                        Hulp bij aanmelden
                        <div class="small">Registreren | Wachtwoord vergeten.</div>
                        <!-- <button type="button" class="checkbox-switch-button"></button> -->
                    </label>
                    <!-- <i class="input-icon fa fa-fw fa-spin fa-circle-o-notch"></i> -->
                    <!-- </div> -->
                </div>
            </div>
            <ul class="list-inline text-center small m-t-md">
                <li><i class="far fa-globe text-muted"></i></li>
                <li><a class="text-muted" href="">English</a></li>
                <li><a class="text-muted" href="">Nederlands</a></li>
                <li><a class="text-muted" href="">Español</a></li>
            </ul>
        </div>
    </form>
    <ul class="list-inline text-center small m-t-md m-b-lg">
        <li><a href="" class="text-muted">Terms and conditions</a></li>
        <li><a href="" class="text-muted">Contact us</a></li>
    </ul>
    <div class="webmail-bg"></div>
    <div style="height: 0; overflow: hidden;">
        <svg class="heroicon" version="1.1" xmlns="http://www.w3.org/2000/svg" width="0" height="0">
            <defs>
                <lineargradient id="gradient-linear">
                    <stop class="stop1" offset="0%"></stop>
                    <stop class="stop2" offset="100%"></stop>
                </lineargradient>
            </defs>
        </svg>
    </div>
</body>

</html>
