<?php

declare(strict_types=1);

?>
<div class="row">
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr><th>ID</th><td><?= $user->user_id ?></td></tr>
            <tr><th>Gebruikersnaam</th><td><?= $user->user_name ?></td></tr>
            <tr><th>Session ID</th><td><?= $user->session_id ?></td></tr>
            <tr><th>E-mailadres</th><td><?= $user->user_email ?></td></tr>
            <tr><th>Account actief</th><td><?= $user->user_active ? 'Ja' : 'Nee' ?></td></tr>
            <tr><th>Account verwijderd</th><td><?= $user->user_deleted ? 'Ja' : 'Nee' ?></td></tr>
            <tr><th>Laatste aanmelding</th><td><?= $user->user_last_login_timestamp->format('Y-m-d H:i:s') ?></td></tr>
            <tr><th>Mislukte aanmeldingen</th><td><?= $user->user_failed_logins ?></td></tr>
            <tr><th>Laatste mislukte aanmelding</th><td><?= $user->user_last_failed_login?->format('Y-m-d H:i:s') ?></td></tr>
            <tr><th>Facebook ID</th><td><?= $user->user_fbid ?></td></tr>
            <tr><th>CreationDate</th><td><?= $user->CreationDate->format('Y-m-d H:i:s') ?></td></tr>
            <tr><th>ModificationDate</th><td><?= $user->ModificationDate->format('Y-m-d H:i:s') ?></td></tr>
        </table>
    </div>
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr>
                <th>Rollen</th>
                <td>
                    <?php foreach ($assignedRoles as $role) { ?>
                        <form method="post" action="/UserManagement/Profile/Role/Unassign">
                            <label><?= $role->role_name ?></label>
                            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                            <input type="hidden" name="role_id" value="<?= $role->role_id ?>">
                            <button type="submit" class="btn btn-danger" title="Rol verwijderen">
                                <span class="fa fa-trash"></span>
                            </button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>Permissies</th>
                <td>
                    <ul>
                        <?php foreach ($userPermissions as $permission) { ?>
                            <li><?= $permission->perm_desc ?></li>
                        <?php } ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Rol toevoegen</th>
                <td>
                    <?php if ($availableRoles !== []) { ?>
                        <form method="post" action="/UserManagement/Profile/Role/Assign">
                            <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                            <select name="role_id">
                                <?php foreach ($availableRoles as $role) { ?>
                                    <option value="<?= $role->role_id ?>"><?= $role->role_name ?></option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-primary ms-2">Toewijzen</button>
                        </form>
                    <?php } else { ?>
                        Alle rollen zijn toegewezen.
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
</div>
