<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Modules\Contracts\ContractMapper;

$contract = ContractMapper::getById($_GET['id']);
if (empty($contract)) {
    Redirect::to('Error/NotFound');
}
$pageName = 'Contract van ' . $contract->band_naam;
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?php require 'inc/buttons.php'; ?>
        <a href="Invoices?id=<?= $contract->id ?>">Facturen bekijken</a>
        <hr>
        <?php require 'inc/view.php'; ?>
        <hr>
        <?php require 'inc/buttons.php'; ?>
    </div>

<?= $this->end();
