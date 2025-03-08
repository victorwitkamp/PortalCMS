<?php



declare(strict_types=1);

use App\Core\View\Text;

$pageName = $title;
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4">
                <a href="/Bank/Import" class="btn btn-success float-right"><span
                        class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a>
            </div>
        </div>
        <table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%;">
            <thead class="thead-dark">
            <tr>
<!--                <th>id</th>-->
                <th>IBAN/BBAN</th>
                <th>Munt</th>
                <th>BIC</th>
                <th>Volgnr</th>
                <th>Datum</th>
                <th>Rentedatum</th>
                <th>Bedrag</th>
                <th>Saldo na trn</th>
                <th>Tegenrekening IBAN/BBAN</th>
                <th>Naam tegenpartij</th>
                <th>Naam uiteindelijke partij</th>
                <th>Naam initiërende partij</th>
                <th>BIC tegenpartij</th>
                <th>Code</th>
                <th>Batch ID</th>
                <th>Transactiereferentie</th>
                <th>Machtigingskenmerk</th>
                <th>Incassant ID</th>
                <th>Betalingskenmerk</th>
                <th>Omschrijving-1</th>
                <th>Omschrijving-2</th>
                <th>Omschrijving-3</th>
                <th>Reden retour</th>
                <th>Oorspr bedrag</th>
                <th>Oorspr munt</th>
                <th>Koers</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($records as $record) {
                ?><tr>
                    <?php foreach ($record as $key => $value) {
                    ?><td><?= $value ?></td><?php
                } ?>
                </tr><?php
            }
            ?>

    </div>




<?= $this->end();
