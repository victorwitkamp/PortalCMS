<?php


declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Core\User\UserMapper;
use App\Core\Controller\AlertController;
use App\Core\View\Text;

$user = UserMapper::getProfileById((int)$this->request->get('id'));
if (empty($user)) {
    return $this->redirectToRoute('errornotfound');
} else {
    $pageName = Text::get('TITLE_PROFILE') . $user->user_name;
} ?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php $Alert = new AlertController();$Alert->renderFeedbackMessages(); ?>
        <form method="post">
            <a href="/UserManagement/Users" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
            <button type="submit" name="deleteuser"
                    onclick="return confirm('Weet je zeker dat je <?= $user->user_name ?> wilt verwijderen?')"
                    class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
        </form>
        <hr>
    </div>
    <div class="container">
        <?php require __DIR__ . '/inc/profile_table.php'; ?>
    </div>

<?= $this->end();
