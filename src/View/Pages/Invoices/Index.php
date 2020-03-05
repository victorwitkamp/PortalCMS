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

$contractId = (int) Request::get('contract');
$year = (int) Request::get('Year');
if (!empty($contractId) && is_numeric($contractId)) {

    $invoices = InvoiceMapper::getByContractId($contractId);
    $contract = ContractMapper::getById($contractId);
    if (empty($contract)) {
        Redirect::to('Error/NotFound');
    } else {
        $pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR') . $contract->band_naam;
    }
} else {
    if (empty($year)) {
        $year = (int) date('Y');
    }
    $invoices = InvoiceMapper::getByYear($year);
    $pageName = Text::get('TITLE_INVOICES');
}


?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<!-- <link rel="stylesheet" type="text/css" href="/dist/datatables.net-select-bs4/css/select.bootstrap4.min.css"> -->

<script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/dist/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="/dist/datatables.net-select-bs4/js/select.bootstrap4.min.js"></script>
<script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-8">
            <h1><?= $pageName ?></h1>
        </div>
        <div class="col-sm-4"><a href="/Invoices/Add" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div>
    </div>
    <form method="post">
        <label><?= Text::get('YEAR') ?></label>
        <input type="number" name="year" value="<?= $year ?>" />
        <button type="submit" class="btn btn-primary" name="showInvoicesByYear"><i class="fab fa-sistrix"></i></button>
    </form>
    <?php
    $years = InvoiceMapper::getYears();
    foreach ($years as $jaar) {
        ?><li><a href="/Invoices?year=<?= $jaar['year'] ?>"><?= $jaar['year'] ?></a> (<?= InvoiceMapper::getInvoiceCountByYear($jaar['year']) ?>)</li><?php
    }
    ?>
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
