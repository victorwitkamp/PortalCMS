<?php

$pageName = 'Lid toevoegen';
$pageType = 'new';
// Authentication::checkAuthentication();
// Authorization::verifyPermission('membership');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
<script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js" async></script>
<script src="/includes/js/jquery-simple-validator.nl.js"></script>
<link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">
<script src="/includes/js/datepicker_membership.js"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

        <div class="container">
            <div class="row mt-5">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <div class="container">
            <?php require 'form.php'; ?>
        </div>

<?= $this->end() ?>