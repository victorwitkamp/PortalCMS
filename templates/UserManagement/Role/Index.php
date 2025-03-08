<?php


declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\Security\Authorization\RoleMapper;
use App\Core\Controller\AlertController;
use App\Core\View\Text;

$Role = RoleMapper::get((int)$this->request->get('id'));
if (empty($Role)) {
    $this->addFlash('danger','Geen resultaten voor opgegeven rol ID.');
    return $this->redirectToRoute('/Error');
} else {
    $pageName = Text::get('TITLE_ROLE') . ': ' . $Role->role_name . ' (rol)';
}
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
        <a href="/UserManagement/Roles" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
        <hr>
    </div>
    <div class="container">
        <?php require __DIR__ . '/inc/RoleDetails.php'; ?>
    </div>

<?= $this->end();
