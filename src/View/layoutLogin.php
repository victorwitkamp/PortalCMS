<?php

use PortalCMS\Core\Config\SiteSetting;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $this->e($title) ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></title>
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= SiteSetting::getStaticSiteSetting('site_theme') ?>/bootstrap.min.css">
    <link rel="stylesheet" href="/includes/LoginNewStyle.css" />
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="/dist/cookieconsent/build/cookieconsent.min.js" async></script>
    <script src="/includes/js/cookieconsent.init.js" async></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> -->
    <?= $this->section('head-extra') ?>
</head>

<body>
    <?= $this->section('body') ?>
</body>

</html>
