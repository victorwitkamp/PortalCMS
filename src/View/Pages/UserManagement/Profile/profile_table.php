<?php
declare(strict_types=1);

use PortalCMS\Core\Security\Authorization\PermissionMapper;
use PortalCMS\Core\Security\Authorization\RoleMapper;
use PortalCMS\Core\Security\Authorization\UserRoleMapper;

?>
<div class="row">
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr>
                <th>ID</th>
                <td><?= $user->user_id ?></td>
            </tr>
            <tr>
                <th>Gebruikersnaam</th>
                <td><?= $user->user_name ?></td>
            </tr>
            <tr>
                <th>Session ID</th>
                <td><?= $user->session_id ?></td>
            </tr>
            <tr>
                <th>E-mailadres</th>
                <td><?= $user->user_email ?></td>
            </tr>
            <tr>
                <th>Account actief</th>
                <td><?= $user->user_active ?></td>
            </tr>
            <tr>
                <th>Account verwijderd</th>
                <td><?= $user->user_deleted ?></td>
            </tr>
            <tr>
                <th>Laatste aanmelding</th>
                <td><?= $user->user_last_login_timestamp ?></td>
            </tr>
            <tr>
                <th>Mislukte aanmeldingen</th>
                <td><?= $user->user_failed_logins ?></td>
            </tr>
            <tr>
                <th>Laatste mislukte aanmelding</th>
                <td><?= $user->user_last_failed_login ?></td>
            </tr>
            <tr>
                <th>Facebook ID</th>
                <td><?= $user->user_fbid ?></td>
            </tr>
            <tr>
                <th>CreationDate</th>
                <td><?= $user->CreationDate ?></td>
            </tr>
            <tr>
                <th>ModificationDate</th>
                <td><?= $user->ModificationDate ?></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr>
                <th>Rollen</th>
                <td>
                    <?php
                    $Roles = UserRoleMapper::getByUserId($user->user_id);
                    if (!empty($Roles)) {
                        foreach ($Roles as $Role) { ?>
                        <form method="post">
                            <label><?= $Role->role_name ?></label>
                            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                            <input type="hidden" name="role_id" value="<?= $Role->role_id ?>">
                            <button type="submit" name="unassignrole" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                        </form>
                        <?php }
                    } ?>
                </td>
            </tr>
            <tr>
                <th>Permissies</th>
                <td>
                    <?php
                    $UserPermissions = PermissionMapper::getPermissionsByUserId($user->user_id);
                    if (!empty($UserPermissions)) {
                        foreach ($UserPermissions as $UserPermission) {
                            echo '<li>';
                            echo $UserPermission->perm_desc;
                            echo '</li>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Rol toevoegen</th>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                        <?php
                        $roles = RoleMapper::getRoles();
                        if (!empty($roles)) { ?>
                            <select name='role_id'>
                            <?php foreach ($roles as $role) { ?>
                                <option value="<?= $role->role_id ?>"><?= $role->role_name ?></option>
                            <?php } ?>
                            </select>
                        <?php } ?>
                        <input type="submit" name="assignrole" value="Toewijzen" class="btn btn-primary ml-2">
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
