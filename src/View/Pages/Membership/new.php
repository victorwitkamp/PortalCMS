<?php
declare(strict_types=1);

$pageName = 'Lid toevoegen';
$pageType = 'new';
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
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
            <?php require __DIR__ . 'inc\form.php'; ?>
        </div>

<?= $this->end();
