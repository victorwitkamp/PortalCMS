<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceMapper;

$pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR_ID') . ': ' . Request::get('id');

$contract = ContractMapper::getById(Request::get('id'));
if (empty($contract)) {
    Redirect::to('Error/Error');
}
$pageName = 'Facturen voor ' . $contract->band_naam;
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <hr>
        <?php
        $invoices = InvoiceMapper::getByContractId((int) Request::get('id'));
        if (!empty($invoices)) {
            include_once DIR_VIEW . 'Pages/Invoices/invoices_table.php';
        } else {
            echo Text::get('LABEL_NOT_FOUND');
        } ?>
    </div>

<?= $this->end();