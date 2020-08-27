<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$Role = RoleMapper::get((int) Request::get('id'));
if (empty($Role)) {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven rol ID.');
    Redirect::to('Error/Error');
} else {
    $pageName = Text::get('TITLE_ROLE') . ': ' . $Role->role_name . ' (rol)';
}
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <a href="/UserManagement/Roles" class="btn btn-sm btn-outline-primary"><span class="fa fa-arrow-left"></span></a>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <?php require __DIR__ . '/inc/RoleDetails.php'; ?>
    </div>

<?= $this->end();
