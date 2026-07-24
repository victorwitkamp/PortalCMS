<?php

declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<h3><?= Text::get('LABEL_ROLE_GENERAL') ?></h3>
<table class="table table-striped table-condensed">
    <tbody>
    <tr><th>ID</th><td><?= $role->role_id ?></td></tr>
    <tr><th>Naam</th><td><?= $role->role_name ?></td></tr>
    <tr>
        <th><?= Text::get('LABEL_ROLE_PERMISSIONS') ?></th>
        <td>
            <?php if ($activePermissions !== []) { ?>
                <table class="table table-sm table-striped table-hover table-dark">
                    <thead class="table-dark">
                    <tr><th>ID</th><th>Permissie</th><th>Acties</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($activePermissions as $permission) { ?>
                        <tr>
                            <td><?= $permission->perm_id ?></td>
                            <td><?= $permission->perm_desc ?></td>
                            <td>
                                <form method="post" action="/UserManagement/Role/Permission/Unassign">
                                    <input type="hidden" name="role_id" value="<?= $role->role_id ?>">
                                    <input type="hidden" name="perm_id" value="<?= $permission->perm_id ?>">
                                    <button type="submit"
                                            onclick="return confirm('Weet u zeker dat u <?= $permission->perm_desc ?> wilt verwijderen?')"
                                            class="btn btn-danger ms-2">
                                        <span class="fa fa-trash"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                Nog geen permissies...
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>

<h3><?= Text::get('LABEL_ROLE_ADD_PERMISSION') ?></h3>
<p>Een rol kan meerdere permissies hebben. Kies hieronder een gewenste permissie om toe te voegen aan de rol.</p>
<?php if ($selectablePermissions !== []) { ?>
    <form method="post" action="/UserManagement/Role/Permission/Assign">
        <input type="hidden" name="role_id" value="<?= $role->role_id ?>">
        <label class="control-label">Permission</label>
        <select name="perm_id">
            <?php foreach ($selectablePermissions as $permission) { ?>
                <option value="<?= $permission->perm_id ?>">
                    <?= $permission->perm_id . '. ' . $permission->perm_desc ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="btn btn-primary ms-2">Toewijzen</button>
    </form>
<?php } else { ?>
    <p>Geen permissies om toe te wijzen</p>
<?php } ?>
