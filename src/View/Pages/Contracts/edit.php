<?php

use PortalCMS\Controllers\ErrorController;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
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

    <script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js" async></script>
    <link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <script>
        $(function() {
            $('#datetimepicker1').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
        $(function() {
            $('#datetimepicker2').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
        $(function() {
            $('#datetimepicker3').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
        $(function() {
            $('#datetimepicker4').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'nl',
                viewMode: 'years'
            });
        });
    </script>
    <?php //PortalCMS_JS_JQuery_Simple_validator();?>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h3><?= $pageName ?></h3>
        </div>
    </div>
    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <?php require 'inc/form_edit.php'; ?>
    </div>

<?= $this->end();
