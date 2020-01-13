<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_PROFILE');

$user = UserPDOReader::getProfileById((int) $_GET['id']);
if (empty($user)) {
    Redirect::to('Error/NotFound');
} else {
    $pageName = Text::get('TITLE_PROFILE') . $user->user_name;
} ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

        <div class="container">
            <div class="row mt-5">
                <h1>Profiel van: <?= $user->user_name ?></h1>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post">
                <a href="/UserManagement/Users" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
                <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                <button type="submit" name="deleteuser" onclick="return confirm('Weet je zeker dat je <?= $user->user_name ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
            </form>
            <hr>
        </div>
        <div class="container-fluid">
            <?php require 'profile_table.php'; ?>
        </div>

<?= $this->end();
