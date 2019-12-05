<?php

use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_ROLE_MANAGEMENT'); ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <?php Alert::renderFeedbackMessages(); ?>
        <hr>
        <table class="table table-sm table-striped table-hover table-dark">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Rol</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <?php

            $Roles = RoleMapper::getRoles();
            if (!empty($Roles)) { ?>
                <tbody>
                    <?php foreach ($Roles as $Role) { ?>
                        <tr>
                            <td><?= $Role->role_id ?></td>
                            <td><?= $Role->role_name ?></td>
                            <td>
                                <form method="post">
                                    <a href="/UserManagement/Role/?id=<?= $Role->role_id ?>" title="Rol beheren" class="btn btn-primary btn-sm">
                                        <span class="fa fa-cog"></span>
                                    </a>
                                    <input type="hidden" name="role_id" value="<?= $Role->role_id ?>">
                                    <button type="submit" name="deleterole" class="btn btn-danger btn-sm" onclick="return confirm('Weet u zeker dat u de rol <?= $Role->role_name ?> wilt verwijderen?')">
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
        <form method="post">
            <input type="text" name="role_name">
            <button type="submit" name="addrole" class="btn btn-danger btn-sm">Toevoegen</button>
        </form>
    </div>

<?= $this->end() ?>
