<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_ROLE_MANAGEMENT');
Auth::checkAuthentication();
if (!Permission::hasPrivilege("role-management")) {
    Redirect::permissionerror();
    die();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_ROOT.'includes/nav.php'; ?>

<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <!-- <div class="col-sm-4"><a href="#" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div> -->
            </div>
            <?php
            Util::DisplayMessage(); View::renderFeedbackMessages(); ?>
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

                    $stmt = DB::conn()->prepare("SELECT * FROM roles ORDER BY role_id ASC");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        echo '<tbody>';
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>'.$row['role_id'].'</td>';
                            echo '<td>'.$row['role_name'].'</td>
                            <td>
                            <a href="role.php?role_id='.$row['role_id'].'" title="Rol beheren" class="btn btn-primary btn-sm"><span class="fa fa-cog"></span></a>
                            <form method="post">
                            <input type="hidden" name="role_id" value="'.$row['role_id'].'">
                            <button type="submit" name="deleterole" class="btn btn-danger btn-sm" onclick="return confirm(\'Weet u zeker dat u de rol '.$row['role_name'].' wilt verwijderen?\')"><span class="fa fa-trash"></span></button>
                            </form>
                            </td>
                            </tr>';
                        }
                        echo '</tbody>';
                    } else {
                        echo '<tr><td colspan="8">Ontbrekende gegevens..</td></tr>';
                    }
                    ?>
                </table>
                <hr>
                <h3>Nieuwe rol</h3>
                <form method="post">
                    <input type="text" name="role_name">

                <button type="submit" name="addrole" class="btn btn-danger btn-sm">Toevoegen</button>
                </form>
        </div>
    </div>
</main>
<?php require DIR_ROOT.'includes/footer.php'; ?>
</body>
</html>