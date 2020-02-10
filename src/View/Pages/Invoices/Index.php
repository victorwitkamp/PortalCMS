<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Invoices\InvoiceMapper;

$pageName = Text::get('TITLE_INVOICES');
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
    <hr>
    <?php Alert::renderFeedbackMessages(); ?>
</div>

<?php $invoices = InvoiceMapper::getAll(); ?>

<?php if (!empty($invoices)) { ?>
    <div class="container-fluid">
        <?php include_once DIR_VIEW . 'Pages/Invoices/table.php'; ?>
    </div>
<?php } else { ?>
    <div class="container">
        <?= 'Geen facturen gevonden.' ?>
    </div>
<?php } ?>

<?= $this->end()
