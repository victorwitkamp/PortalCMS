<?php
declare(strict_types=1);

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\RolePermissionMapper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_ROLE');

$Role = RoleMapper::get((int) $_GET['id']);
if (empty($Role)) {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven rol ID.');
    Redirect::to('Error/Error');
}
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <h1><?= Text::get('TITLE_ROLE') ?>: <?= $Role->role_name ?> (rol)</h1>
    </div>
    <?php Alert::renderFeedbackMessages();
    if ($Role) { ?>
        <h3><?= Text::get('LABEL_ROLE_GENERAL') ?></h3>
        <table class="table table-striped table-condensed">
            <!-- <thead class="thead-dark"> -->
            <tbody>
                <tr>
                    <th>ID</th>
                    <td><?= $Role->role_id ?></td>
                </tr>
                <tr>
                    <th>Naam</th>
                    <td><?= $Role->role_name ?></td>
                </tr>
                <tr>
                    <th><?= Text::get('LABEL_ROLE_PERMISSIONS') ?></th>
                    <td>
                    <?php
                        $ActivePerissions = RolePermissionMapper::getRolePermissions((int) $_GET['id']);
                    if ($ActivePerissions) { ?>
                        <table class="table table-sm table-striped table-hover table-dark">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Permissie</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ActivePerissions as $Permission) { ?>
                                    <tr>
                                        <td><?= $Permission['perm_id'] ?></td>
                                        <td><?= $Permission['perm_desc'] ?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="role_id" value="<?= $_GET['id'] ?>">
                                                <input type="hidden" name="perm_id" value="<?= $Permission['perm_id'] ?>">
                                                <?php $msg = 'Weet u zeker dat u ' . $Permission['perm_desc'] . ' wilt verwijderen?'; ?>
                                                <button type="submit" name="deleterolepermission" onclick="return confirm('<?= $msg ?>')" class="btn btn-danger ml-2"><span class="fa fa-trash"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else {
                        echo 'Nog geen permissies...';
                    } ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php }

    if ($Role) { ?>
        <h3><?= Text::get('LABEL_ROLE_ADD_PERMISSION') ?></h3>
        <p>Een rol kan meerdere permissies hebben. Kies hieronder een gewenste permissie om toe te voegen aan de rol.<p>
                <?php
                    $selectablePermissions = RolePermissionMapper::getRoleSelectablePermissions((int) $_GET['id']);
                if ($selectablePermissions) { ?>
                    <form method="post">
                        <input type="hidden" name="role_id" value="<?= $_GET['id'] ?>">
                        <label class="control-label">Permission</label>
                        <select name='perm_id'>
                            <?php foreach ($selectablePermissions as $selectablePermission) { ?>
                                <option value="<?= $selectablePermission['perm_id'] ?>"><?= $selectablePermission['perm_id'] . '. ' . $selectablePermission['perm_desc'] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="submit" name="setrolepermission" value="Toewijzen" class="btn btn-primary ml-2">
                    </form>

                    <?php
                } else {
                    ?>
                    <p>Geen permissies om toe te wijzen</p>
                    <?php
                }
    } ?>

</div>

<?= $this->end();
