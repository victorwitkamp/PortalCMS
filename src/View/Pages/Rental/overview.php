<?php

use PortalCMS\Core\Database\DB;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_OVERVIEW');
// Authentication::checkAuthentication();
// Authorization::verifyPermission('rental-contracts');
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
                <div class="col-sm-12">
                    <h1><?= $pageName ?></h1>
                </div>
            </div>
            <hr>
            <?php
                Alert::renderFeedbackMessages();
                $stmt = DB::conn()->query('SELECT count(id) as NumberOfContracts FROM contracts');
                $row = $stmt->fetchColumn();
                echo 'Totaal aantal contracten: ' . $row . '<br>';
                $stmt = DB::conn()->query('SELECT count(id) as NumberOfInvoices FROM invoices');
                $row = $stmt->fetchColumn();
                echo 'Totaal aantal facturen: ' . $row;
            ?>
        </div>
<?= $this->end() ?>
