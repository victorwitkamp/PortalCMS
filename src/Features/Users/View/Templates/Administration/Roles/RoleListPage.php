<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_ROLE_MANAGEMENT'); ?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
        <hr>
        <table class="table table-sm table-striped table-hover table-dark">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Acties</th>
            </tr>
            </thead>
            <?php

            if (!empty($roles)) { ?>
                <tbody>
                <?php foreach ($roles as $role) { ?>
                    <tr>
                        <td><?= $role->role_id ?></td>
                        <td><?= $role->role_name ?></td>
                        <td>
                            <form method="post" action="/UserManagement/Roles/Delete">
                                <a href="/UserManagement/Role/?id=<?= $role->role_id ?>" title="Rol beheren"
                                   class="btn btn-primary btn-sm">
                                    <span class="fa fa-cog"></span>
                                </a>
                                <input type="hidden" name="role_id" value="<?= $role->role_id ?>">
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Weet u zeker dat u de rol <?= $role->role_name ?> wilt verwijderen?')">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } else { ?>
                <tr>
                    <td colspan="8">Ontbrekende gegevens..</td>
                </tr>
            <?php } ?>
        </table>
        <hr>
        <h3>Nieuwe rol</h3>
        <form method="post" action="/UserManagement/Roles/Create">
            <input type="text" name="role_name">
            <button type="submit" class="btn btn-primary btn-sm">Toevoegen</button>
        </form>
    </div>

<?= $this->end();
