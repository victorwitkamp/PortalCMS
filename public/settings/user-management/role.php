<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Authorization\Role;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\RolePermissionMapper;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_ROLE');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("user-management")) {
    Redirect::permissionError();
    die();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();


$Role = Role::get($_GET['role_id']);
if (!$Role) {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven rol ID.");
    Redirect::error();
}
?>
</head>
<body>
    <?php require DIR_ROOT.'includes/nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h1><?php echo Text::get('TITLE_ROLE'); ?>: <?php
                    if (!empty($Role['role_name'])) {
                        echo $Role['role_name'].' (rol)';
                    }?></h1>
                </div>

                <?php Alert::renderFeedbackMessages();

                if ($Role) { ?>
                    <!-- <h3><?php //echo Text::get('LABEL_ROLE_GENERAL');?></h3> -->
                    <table class="table table-striped table-condensed">
                        <!-- <thead class="thead-dark"> -->
                        <tbody>
                        <tr>
                        <th>ID</th><td>
                            <?php echo $Role['role_id']; ?>
                        </td>
                        </tr>
                            <th>Naam</th>
                            <td>
                                <?php echo $Role['role_name']; ?>
                            </td>
                        </tr>
                            <th>Permissies</th>
                            <td>

                            <h3><?php //echo Text::get('LABEL_ROLE_PERMISSIONS');?></h3>
                                <?php
                                // $Permissions = RolePermission::getPermissionIds($_GET['role_id']);
                                $Permissions = RolePermissionMapper::getRolePermissions($_GET['role_id']);

                                if ($Permissions) { ?>
                                    <table class="table table-sm table-striped table-hover table-dark">
                                            <thead class="thead-dark">                                <tr>
                                                    <!-- <th>ID</th> -->
                                                    <th>Permissie</th>
                                                    <th>Acties</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // foreach ($Permissions as $row) {
                                            foreach ($Permissions as $Permission) {
                                                ?>


                                                <?php //$Permission = PermissionMapper::getById($row['perm_id']);?>
                                                    <tr>
                                                        <!-- <td><?php //echo $row['perm_id'];?></td> -->
                                                        <td><?php echo $Permission['perm_desc']; ?></td>
                                                        <td>
                                                            <form method="post">
                                                                <input type="hidden" name="role_id" value="<?php echo $_GET['role_id']; ?>">
                                                                <input type="hidden" name="perm_id" value="<?php echo $Permission['perm_id']; ?>">
                                                                            <?php
                                                                            $msg = 'Weet u zeker dat u '.$Permission['perm_desc'].' wilt verwijderen?'; ?>

                                                                <button type="submit" name="deleterolepermission" onclick="return confirm('<?php echo $msg; ?>')" class="btn btn-danger ml-2"><span class="fa fa-trash"></span></button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                            <?php
                                            } ?>
                                            </tbody>
                                    </table>
                                    <?php
                                } else {
                                    echo 'Nog geen permissies...';
                                }
                                ?>
                            </td>
                        </tr>
                        <!-- </thead> -->
                        </tbody>
                    </table>
                <?php }



                if ($Role) { ?>
                    <h3><?php echo Text::get('LABEL_ROLE_ADD_PERMISSION'); ?></h3>
                    <p>Een rol kan meerdere permissies hebben. Kies hieronder
                    een gewenste permissie om toe te voegen aan de rol.<p>
                    <?php

                    $stmt = DB::conn()->query("SELECT * FROM permissions ORDER BY perm_desc ASC");
                    if ($stmt->rowCount() > 0) { ?>

                        <form method="post">
                            <input type="hidden" name="role_id" value="<?php echo $_GET['role_id']; ?>">
                            <label class="control-label">Permission</label>
                            <select name='perm_id'>
                                <?php
                                $perms = $stmt->fetchAll();
                                foreach ($perms as $permrow) { ?>
                                    <option value="<?php echo $permrow['perm_id']; ?>"><?php echo $permrow['perm_desc']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="setrolepermission" value="Toewijzen"
                                class="btn btn-primary ml-2">
                        </form>

                    <?php } else { ?>

                        <p>Geen permissies om toe te wijzen</p>

                    <?php }
                }

                // if ($Role) {

                // }?>

            </div>
        </div>
    </main>
    <?php include DIR_INCLUDES.'footer.php'; ?>
</body>

</html>
