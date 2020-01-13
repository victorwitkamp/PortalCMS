<?php

$pageName = 'Contract toevoegen';
$loadData = false;
?>

<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js" async></script>
    <link rel="stylesheet" type="text/css"
          href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <script>
        $(function () {
            $('#datetimepicker1').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years',
                defaultDate: '01-01-1990'
            });
        });
        $(function () {
            $('#datetimepicker2').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
        $(function () {
            $('#datetimepicker3').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
    </script>
    <!-- <script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css"> -->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?php require 'inc/form_new.php'; ?>
    </div>

<?= $this->end();
