<?php

$pageName = 'Contract toevoegen';
$loadData = false;
?>

<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script src="/dist/moment/min/moment.min.js"></script>
<script src="/dist/moment/locale/nl.js"></script>
<script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
<script>
    $(document).ready(function() {
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY',
            locale: 'nl',
            viewMode: 'years',
            defaultDate: '01-01-1990'
        });
        $('#datetimepicker2').datetimepicker({
            format: 'DD-MM-YYYY',
            locale: 'nl',
            viewMode: 'years'
        });
        $('#datetimepicker3').datetimepicker({
            format: 'DD-MM-YYYY',
            locale: 'nl',
            viewMode: 'years'
        });
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
    <?php require DIR_VIEW . 'Pages/Contracts/inc/form_new.php'; ?>
</div>

<?= $this->end();
