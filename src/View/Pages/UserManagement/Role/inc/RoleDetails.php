<?php
declare(strict_types=1);

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authorization\RolePermissionMapper;
use PortalCMS\Core\View\Text;

if ($Role) { ?>
    <h3><?= Text::get('LABEL_ROLE_GENERAL') ?></h3>
    <table class="table table-striped table-condensed">
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
                $ActivePerissions = RolePermissionMapper::getRolePermissions((int) Request::get('id'));
                if (!empty($ActivePerissions)) { ?>
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
                                    <input type="hidden" name="role_id" value="<?= (int) Request::get('id') ?>">
                                    <input type="hidden" name="perm_id" value="<?= $Permission['perm_id'] ?>">
                                    <button type="submit" name="deleterolepermission"
                                            onclick="return confirm('<?= 'Weet u zeker dat u ' . $Permission['perm_desc'] . ' wilt verwijderen?' ?>')"
                                            class="btn btn-danger ml-2">
                                        <span class="fa fa-trash"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    </table><?php
                } else {
                    echo 'Nog geen permissies...';
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
<?php }

if ($Role) {
    ?><h3><?= Text::get('LABEL_ROLE_ADD_PERMISSION') ?></h3>
    <p>Een rol kan meerdere permissies hebben. Kies hieronder een gewenste permissie om toe te voegen aan de rol.<p>
    <?php
    $selectablePermissions = RolePermissionMapper::getRoleSelectablePermissions((int) Request::get('id'));
    if (!empty($selectablePermissions)) {
        ?>
        <form method="post">
        <input type="hidden" name="role_id" value="<?= (int) Request::get('id') ?>">
        <label class="control-label">Permission</label>
        <select name='perm_id'>
            <?php foreach ($selectablePermissions as $selectablePermission) { ?>
                <option value="<?= $selectablePermission['perm_id'] ?>"><?= $selectablePermission['perm_id'] . '. ' . $selectablePermission['perm_desc'] ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" name="setrolepermission" value="Toewijzen" class="btn btn-outline-primary ml-2">
        </form><?php
    } else {
        ?><p>Geen permissies om toe te wijzen</p><?php
    }
}
