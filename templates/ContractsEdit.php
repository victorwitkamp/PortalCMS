<?php


declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\Controller\AlertController;
use App\Modules\Contracts\ContractMapper;

$contract = ContractMapper::getById((int)$this->request->get('id'));
if (empty($contract)) {
    return $this->redirectToRoute('errornotfound');
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
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
        <?php require DIR_VIEW . 'Pages/Contracts/inc/form.php'; ?>
    </div>

<?= $this->end();
