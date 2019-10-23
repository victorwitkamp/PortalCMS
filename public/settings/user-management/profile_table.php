<?php

use PortalCMS\Core\Authorization\PermissionMapper;
use PortalCMS\Core\Authorization\Role;
use PortalCMS\Core\Authorization\UserRoleMapper;
use PortalCMS\Core\Database\DB;

?>
<div class="row">
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr>
                <th>ID</th>
                <td><?php
                    echo $row['user_id']; ?></td>
            </tr>
            <tr>
                <th>Gebruikersnaam</th>
                <td><?php echo $row['user_name']; ?></td>
            </tr>
            <tr>
                <th>Session ID</th>
                <td><?php echo $row['session_id']; ?></td>
            </tr>
            <tr>
                <th>E-mailadres</th>
                <td><?php echo $row['user_email']; ?></td>
            </tr>
            <tr>
                <th>Account actief</th>
                <td><?php echo $row['user_active']; ?></td>
            </tr>
            <tr>
                <th>Account verwijderd</th>
                <td><?php echo $row['user_deleted']; ?></td>
            </tr>
            <tr>
                <th>Laatste aanmelding</th>
                <td><?php echo $row['user_last_login_timestamp']; ?></td>
            </tr>
            <tr>
                <th>Mislukte aanmeldingen</th>
                <td><?php echo $row['user_failed_logins']; ?></td>
            </tr>
            <tr>
                <th>Laatste mislukte aanmelding</th>
                <td><?php echo $row['user_last_failed_login']; ?></td>
            </tr>
            <tr>
                <th>Facebook ID</th>
                <td><?php echo $row['user_fbid']; ?></td>
            </tr>
            <tr>
                <th>CreationDate</th>
                <td><?php echo $row['CreationDate']; ?></td>
            </tr>
            <tr>
                <th>ModificationDate</th>
                <td><?php echo $row['ModificationDate']; ?></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class="table table-striped table-condensed">
            <tr>
                <th>Rollen</th>
                <td>
                    <?php
                    $Roles = UserRoleMapper::getByUserId($row['user_id']);
                    foreach ($Roles as $Role) { ?>
                        <form method="post">
                            <label><?php echo Role::get($Role['role_id'])['role_name']; ?></label>
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <input type="hidden" name="role_id" value="<?php echo $Role['role_id']; ?>">
                            <button type="submit" name="unassignrole" class="btn btn-danger"><span class="fa fa-trash"></span></button>
                        </form>
                    <?php }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Permissies</th>
                <td>
                    <?php
                    $UserPermissions = PermissionMapper::getPermissionsByUserId($row['user_id']);
                    foreach ($UserPermissions as $UserPermission) {
                        echo '<li>';
                        echo $UserPermission['perm_desc'];
                        echo '</li>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Rol toevoegen</th>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <?php
                        $stmt = DB::conn()->query('SELECT * FROM roles ORDER BY role_id ASC');
                        if ($stmt->rowCount() > 0) {
                            echo "<select name='role_id'>";
                            while ($rowroles = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?php echo $rowroles['role_id']; ?>"><?php echo $rowroles['role_name']; ?>
                                <?php
                            }
                            echo '</select>';
                        }
                        ?>
                            <input type="submit" name="assignrole" value="Toewijzen" class="btn btn-primary ml-2">
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>