<?php
declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($title) ?> - <?= SiteSetting::get('site_name') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= SiteSetting::get('site_theme') ?>/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <script src="/dist/cookieconsent/build/cookieconsent.min.js"></script>
    <script src="/includes/js/cookieconsent.init.js"></script>
    <?= $this->section('head-extra') ?>
</head>
<body>
    <?php require DIR_VIEW . 'Parts/Nav.php'; ?>
    <main>
        <div class="content">
            <?=$this->section('main-content')?>
        </div>
    </main>
    <?php require DIR_VIEW . 'Parts/Footer.php'; ?>
    <?=$this->section('scripts')?>
</body>
</html>
