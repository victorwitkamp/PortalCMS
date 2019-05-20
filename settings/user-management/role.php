<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_ROLE');
Auth::checkAuthentication();
Auth::checkAdminAuthentication();
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();


$Role = Role::get($_GET['role_id']);
if (!$Role) {
    $_SESSION['response'][] = array("status"=>"error", "message"=>"Geen resultaten voor opgegeven rol ID.");
} else {
    $row = $Role;
}
?>
</head>
<body>
    <?php require DIR_ROOT.'includes/nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h1><?php echo Text::get('TITLE_ROLE'); ?>: <?php if (!empty($row['role_name'])) { echo $row['role_name']; }?></h1>
                </div>

                <?php Util::DisplayMessage();

                if ($Role) { ?>
                <h3><?php echo Text::get('LABEL_ROLE_GENERAL'); ?></h3>
                <table class="table table-sm table-striped table-hover table-dark">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Naam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $row['role_id']; ?>
                            </td>
                            <td>
                                <?php echo $row['role_name']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php }

                if ($Role) { ?>
                <h3><?php echo Text::get('LABEL_ROLE_PERMISSIONS'); ?></h3>

                    <?php
                    $stmt = DB::conn()->prepare("SELECT * FROM role_perm where role_id=".$_GET['role_id']." ORDER BY perm_id ASC");
                    $stmt->execute([$_GET['role_id']]);
                    if ($stmt->rowCount() > 0) { ?>

                        <table class="table table-sm table-striped table-hover table-dark">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Permissie</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>

                                <tr>
                                    <td><?php echo $row['perm_id']; ?></td>
                                    <td><?php
                                    $Permission = Permission::get($row['perm_id']);
                                    echo $Permission['perm_desc'];
                                    ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="role_id"
                                                value="<?php echo $row['role_id']; ?>">
                                            <input type="hidden" name="perm_id"
                                                value="<?php echo $row['perm_id']; ?>">
                                            <input type="submit" name="deleterolepermission"
                                                value="Verwijderen" class="btn btn-danger ml-2">
                                        </form>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>

                    <?php }
                }

                if ($Role) { ?>
                    <h3><?php echo Text::get('LABEL_ROLE_ADD_PERMISSION'); ?></h3>
                    <p>Een rol kan meerdere permissies hebben. Kies hieronder
                    een gewenste permissie om toe te voegen aan de rol.<p>
                    <?php
                    $stmt = DB::conn()->prepare("SELECT * FROM permissions ORDER BY perm_id ASC");
                    $stmt->execute();
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

                        <p>Leeg</p>

                    <?php }
                } ?>

            </div>
        </div>
    </main>
    <?php require DIR_ROOT.'includes/footer.php'; ?>
</body>

</html>