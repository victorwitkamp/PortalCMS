<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Contracts\ContractMapper;

$contract = ContractMapper::getById((int)Request::get('id'));
if (empty($contract)) {
    Redirect::to('Error/NotFound');
} else {
    $pageType = 'edit';
    $pageName = 'Contract van ' . $contract->band_naam . ' wijzigen';
}
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h3><?= $pageName ?></h3>
        </div>
    </div>
    <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <?php require DIR_VIEW . 'Pages/Contracts/inc/form.php'; ?>
    </div>

<?= $this->end();
