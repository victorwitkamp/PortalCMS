<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Contracts\ContractMapper;

$contract = ContractMapper::getById((int)Request::get('id'));
if (empty($contract)) {
    Redirect::to('Error/NotFound');
}
$pageName = 'Contract van ' . $contract->band_naam;
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <div class="container">
        <?php require __DIR__ . '/inc/buttons.php'; ?>
        <a href="/Invoices?contract=<?= $contract->id ?>">Facturen bekijken</a>
        <hr>
        <?php require __DIR__ . '/inc/view.php'; ?>
        <hr>
        <?php require __DIR__ . '/inc/buttons.php'; ?>
    </div>

<?= $this->end();
