<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);


$pageType = 'edit';
$pageName = 'Contract van ' . $contract->band_naam . ' wijzigen';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h3><?= $pageName ?></h3>
        </div>
    </div>
    <div class="container">
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <?= $this->insert('Contracts::Partials/ContractForm', [
            'pageType' => $pageType,
            'contract' => $contract,
        ]) ?>
    </div>

<?= $this->end();
