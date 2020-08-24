<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$user = UserMapper::getProfileById((int) Request::get('id'));
if (empty($user)) {
    Redirect::to('Error/NotFound');
} else {
    $pageName = Text::get('TITLE_PROFILE') . $user->user_name;
} ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <form method="post">
            <a href="/UserManagement/Users" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
            <button type="submit" name="deleteuser"
                    onclick="return confirm('Weet je zeker dat je <?= $user->user_name ?> wilt verwijderen?')"
                    class="btn btn-sm btn-danger" disabled><span class="fa fa-trash"></span></button>
        </form>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <?php require __DIR__ . '/inc/profile_table.php'; ?>
    </div>

<?= $this->end();
