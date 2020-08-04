<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceMapper;

$contractId = (int)Request::get('contract');
$year = (int)Request::get('year');

if (!empty($contractId) && is_numeric($contractId)) {
    $contract = ContractMapper::getById($contractId);
    if (empty($contract)) {
        Redirect::to('Error/NotFound');
    }
    if (!empty($year)) {
        $invoices = InvoiceMapper::getByContractIdAndYear($contractId, $year);
        $pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR') . $contract->band_naam;
    } else {
        $invoices = InvoiceMapper::getByContractId($contractId);
        $pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR') . $contract->band_naam . ' (voor jaar: ' . $year . ')';
    }
} else {
    if (!empty($year)) {
        $invoices = InvoiceMapper::getByYear($year);
    } else {
        $invoices = InvoiceMapper::getAll();
    }
    $pageName = Text::get('TITLE_INVOICES');
}

?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4"><a href="/Invoices/Add" class="btn btn-success navbar-btn float-right"><span
                            class="fa fa-plus"></span> Toevoegen</a></div>
        </div>

        <ul>
            <li><a href="/Invoices">Alle</a> (<?= InvoiceMapper::getInvoiceCount() ?>)</li>
            <?php
            $years = InvoiceMapper::getYears();
            foreach ($years as $jaar) {
                ?>
                <li><a href="/Invoices?year=<?= $jaar['year'] ?>"><?= $jaar['year'] ?></a>
                (<?= InvoiceMapper::getInvoiceCountByYear($jaar['year']) ?>
                ) <?php if ((int)Request::get('year') === $jaar['year']) {
                    echo ' - Geselecteerd';
                } ?></li><?php
            } ?>
        </ul>


        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>

<?php if (!empty($invoices)) { ?>
    <div class="container-fluid">
        <?php include_once DIR_VIEW . 'Pages/Invoices/Inc/Table.php'; ?>
    </div>
<?php } else { ?>
    <div class="container">
        <?= 'Geen facturen gevonden.' ?>
    </div>
<?php } ?>

<?= $this->end();
