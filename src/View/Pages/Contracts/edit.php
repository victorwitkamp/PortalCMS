<?php
declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Contracts\ContractMapper;

$loadData = true;

$contract = ContractMapper::getById((int) Request::get('id'));
if (empty($contract)) {
    Redirect::to('Error/NotFound');
}
$pageName = 'Contract van ' . $contract->band_naam . ' wijzigen';
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

    <script src="/dist/moment/min/moment.min.js"></script>
    <script src="/dist/moment/locale/nl.js"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <h3><?= $pageName ?></h3>
    </div>
</div>
<div class="container">
    <?php Alert::renderFeedbackMessages(); ?>
    <?php require DIR_VIEW . 'Pages/Contracts/inc/form_edit.php'; ?>
</div>

<?= $this->end();
