<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_ROLE') . ': ' . $role->role_name . ' (rol)';
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <a href="/UserManagement/Roles" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
        <hr>
    </div>
    <div class="container">
        <?= $this->insert(
            'Users::Administration/Roles/Partials/RoleDetailsTable',
            compact('role', 'activePermissions', 'selectablePermissions'),
        ) ?>
    </div>

<?= $this->end();
