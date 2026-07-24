<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);


?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
<!--    <script src="/includes/js/init.datatables.js" class="init"></script>-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new DataTable("#example", {
                scrollX: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/2.3.8/i18n/nl-NL.json"
                },
                paging: false,
                ordering: true,
                order: [[1, "asc"]],
                compact: true,
                select: true,
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ]
            });
        });
    </script>


<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4">
                <a href="/Invoices/Add" class="btn btn-success navbar-btn float-end"><span class="fa fa-plus"></span> Toevoegen</a>
            </div>
        </div>

        <ul>
            <li><a href="/Invoices">Alle</a> (<?= $invoiceCount ?>)</li>
            <?php
            foreach ($years as $availableYear) { ?>
                <li><a href="/Invoices?year=<?= $availableYear ?>"><?= $availableYear ?></a>
                    (<?= $yearCounts[$availableYear] ?? 0 ?>) <?php
                if ($selectedYear === $availableYear) {
                    echo ' - Geselecteerd';
                } ?></li><?php
            } ?>
        </ul>


        <hr>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
    </div>

<?php if (!empty($invoices)) { ?>
    <div class="container-fluid">
        <?= $this->insert('Invoices::Partials/InvoiceTable', compact('invoices', 'mailDates')) ?>
    </div>
<?php } else { ?>
    <div class="container">
        <?= 'Geen facturen gevonden.' ?>
    </div>
<?php } ?>

<?= $this->end();
