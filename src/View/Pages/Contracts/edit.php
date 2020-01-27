<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Contracts\ContractMapper;

$loadData = true;

$contract = ContractMapper::getById(Request::get('id'));
if (empty($contract)) {
    Redirect::to('Error/NotFound');
}
$pageName = 'Contract van ' . $contract->band_naam . ' bewerken';
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <script src="/dist/moment/min/moment.min.js"></script>
    <script src="/dist/moment/locale/nl.js"></script>
    <!-- <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css"> -->
    <script>
        // $(document).ready(function() {
        //     $('#datetimepicker1').datetimepicker({
        //         format: 'DD-MM-YYYY',
        //         locale: 'nl',
        //         viewMode: 'years'
        //     });
        //     $('#datetimepicker2').datetimepicker({
        //         format: 'DD-MM-YYYY',
        //         locale: 'nl',
        //         viewMode: 'years'
        //     });
        //     $('#datetimepicker3').datetimepicker({
        //         format: 'DD-MM-YYYY',
        //         locale: 'nl',
        //         viewMode: 'years'
        //     });
        //     $('#datetimepicker4').datetimepicker({
        //         format: 'DD-MM-YYYY',
        //         locale: 'nl',
        //         viewMode: 'years'
        //     });
        // })
    </script>
    <!-- <script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css"> -->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <h3><?= $pageName ?></h3>
    </div>
</div>
<div class="container">
    <?php Alert::renderFeedbackMessages(); ?>
    <?php require DIR_VIEW . 'Pages/Contracts/inc/form_edit.php'; ?>
</div>

<?= $this->end();
