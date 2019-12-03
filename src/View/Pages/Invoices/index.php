<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Invoices\InvoiceMapper;

$pageName = Text::get('TITLE_INVOICES');
// Authentication::checkAuthentication();
// Authorization::verifyPermission('rental-invoices');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>
    <link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- <script src="/includes/js/init.datatables.js" class="init"></script> -->
<?= $this->end() ?>
<?= $this->push('main-content') ?>
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4"><a href="add.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>

        <div class="container-fluid">
            <?php
            $invoices = InvoiceMapper::getAll();
            if (!empty($invoices)) {
                include_once 'invoices_table.php';
                ?>
<script src="/includes/js/init.datatables.js" class="init"></script>
                <?php
            } else {
                echo 'Geen facturen gevonden.';
            }
            ?>
        </div>
<?= $this->end() ?>
