<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;

$pageName = Text::get('TITLE_CONTRACTS');

$contracts = ContractMapper::get();
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
            <div class="col-sm-4"><a href="/Contracts/New" class="btn btn-success navbar-btn float-right"><span
                            class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a></div>
        </div>
        <hr>
        <?php
        Alert::renderFeedbackMessages();
        if ($contracts) { ?>
            <table id="example" class="table table-sm table-striped table-hover" style="width:100%;">
                <thead class="thead-dark">
                <tr>
                    <th>Huurder</th>
                    <th>Klantcode</th>
                    <th>Prijs per maand</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($contracts as $contract) { ?>
                    <tr>
                        <td><a href="/Contracts/Details?id=<?= $contract->id ?>"><?= $contract->band_naam ?></a></td>
                        <td><?= $contract->bandcode ?></td>
                        <td><?= $contract->kosten_totaal ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php
        } else {
            echo Text::get('LABEL_NOT_FOUND');
        }
        ?>
    </div>

<?= $this->end();
