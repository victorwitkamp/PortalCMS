<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Contract toevoegen';
$pageType = 'new';
?>

<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <h1><?= $pageName ?></h1>
    </div>
</div>
<div class="container">
    <?php require DIR_VIEW . 'Pages/Contracts/inc/form.php'; ?>
</div>

<?= $this->end();
