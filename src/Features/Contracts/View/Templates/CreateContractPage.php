<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Contract toevoegen';
$pageType = 'new';
?>

<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?= $this->insert('Contracts::Partials/ContractForm', [ 'pageType' => $pageType ]) ?>
    </div>

<?= $this->end();
