<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

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
    <link rel="stylesheet" type="text/css"
          href="/dist/bootswatch/dist/<?= SiteSetting::get('site_theme') ?>/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css"/>
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">
    <script src="/dist/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->section('head-extra') ?>
</head>
<body>
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
<main>
    <div class="content">
        <?= $this->section('main-content') ?>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
<?= $this->section('scripts') ?>

<script src="/dist/cookieconsent/build/cookieconsent.min.js"></script>
<script src="/includes/js/cookieconsent.init.js"></script>
</body>
</html>
