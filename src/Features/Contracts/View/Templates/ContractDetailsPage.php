<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Contract van ' . $contract->band_naam;
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?= $this->insert('Contracts::Partials/ContractActionButtons', [ 'contract' => $contract ]) ?>
        <a href="/Invoices?contract=<?= $contract->id ?>">Facturen bekijken</a>
        <hr>
        <?= $this->insert('Contracts::Partials/ContractDetails', [ 'contract' => $contract ]) ?>
        <hr>
        <?= $this->insert('Contracts::Partials/ContractActionButtons', [ 'contract' => $contract ]) ?>
    </div>

<?= $this->end();
