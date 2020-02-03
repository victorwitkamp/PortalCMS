<?php
declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Alert;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $this->e($title) ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></title>
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= SiteSetting::getStaticSiteSetting('site_theme') ?>/bootstrap.min.css">
    <link rel="stylesheet" href="/includes/css/LoginNewStyle.css" />
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="/dist/cookieconsent/build/cookieconsent.min.js"></script>
    <script src="/includes/js/cookieconsent.init.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">
    <?= $this->section('head-extra') ?>
</head>

<body>
    <?= $this->section('body-start') ?>

    <div class="container-fluid container-auth">
        <div class="auth-brand m-t-md m-b-md"><?= SiteSetting::getStaticSiteSetting('site_name') ?></div>
    </div>

    <form method="post">
        <div class="container-fluid container-auth">
            <div class="panel panel-auth">
                <div class="panel-heading">
                    <h2 id="title-container" class="panel-title text-center"><?= $this->e($title) ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></h2>
                    <?php Alert::renderFeedbackMessages(); ?>
                </div>
                <div class="panel-body">
                    <?= $this->section('body') ?>
                </div>
                <div class="panel-footer">
                    <label>Hulp bij aanmelden<div class="small"><del>Registreren</del> | <a href="/Login/Activate">Activeren</a> | <a href="/Login/RequestPasswordReset">Wachtwoord vergeten</a></div></label>
                </div>
            </div>
            <ul class="list-inline text-center small m-t-md">
                <li><i class="fas fa-globe text-muted"></i></li>
                <li><a class="text-muted" href="#"><del>English</del></a></li>
                <li><a class="text-muted" href="#"><del>Nederlands</del></a></li>
                <!-- <li><a class="text-muted" href="">Español</a></li> -->
            </ul>
        </div>
    </form>
    <ul class="list-inline text-center small m-t-md m-b-lg">
        <li><a href="#" class="text-muted"><del>Terms and conditions</del></a></li>
        <li><a href="#" class="text-muted"><del>Contact us</del></a></li>
    </ul>
    <div class="webmail-bg"></div>

</body>

</html>
