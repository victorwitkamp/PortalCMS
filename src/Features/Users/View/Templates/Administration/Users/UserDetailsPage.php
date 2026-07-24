<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_PROFILE') . $user->user_name;
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <form method="post" action="/UserManagement/Users/Delete">
            <a href="/UserManagement/Users" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
            <button type="submit"
                    onclick="return confirm('Weet je zeker dat je <?= $user->user_name ?> wilt verwijderen?')"
                    class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
        </form>
        <hr>
    </div>
    <div class="container">
        <?= $this->insert(
            'Users::Administration/Users/Partials/UserDetailsTable',
            compact('user', 'assignedRoles', 'availableRoles', 'userPermissions'),
        ) ?>
    </div>

<?= $this->end();
