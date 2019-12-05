<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Contracts\ContractMapper;


// Authentication::checkAuthentication();
// Authorization::verifyPermission('rental-contracts');
$contract = ContractMapper::getById($_GET['id']);
if (empty($contract)) {
    Session::add('feedback_negative', 'Het contract bestaat niet.');
    Redirect::to('Error/Error');
}
$pageName = 'Contract van ' . $contract->band_naam;
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
        <?php require 'inc/buttons.php'; ?>
        <a href="invoices.php?id=<?= $contract->id ?>">Facturen bekijken</a>
        <hr>
        <?php require 'inc/view.php'; ?>
        <hr>
        <?php require 'inc/buttons.php'; ?>
    </div>
<?= $this->end() ?>
