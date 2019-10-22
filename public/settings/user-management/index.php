<?php

use PDO;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_USER_MANAGEMENT');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("user-management")) {
    Redirect::permissionError();
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
            Alert::renderFeedbackMessages(); ?>
            <hr>
                <table class="table table-sm table-striped table-hover table-dark">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo Text::get('LABEL_USER_ID'); ?></th>
                            <th><?php echo Text::get('LABEL_USER_NAME'); ?></th>
                            <th><?php echo Text::get('LABEL_USER_EMAIL'); ?></th>
                            <th><?php echo Text::get('LABEL_USER_LAST_LOGIN_TIMESTAMP'); ?></th>
                            <th>Profiel</th>
                        </tr>
                    </thead>
                    <?php
                    // $sql = "SELECT * FROM users ORDER BY id ASC";
                    $stmt = DB::conn()->query("SELECT * FROM users ORDER BY user_id ASC");

                    // $result = $u->Database->db->query($sql);
                    if ($stmt->rowCount() > 0) {
                        echo '<tbody>';
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>
                            <td>'.$row['user_id'].'</td>
                            <td>'.$row['user_name'].'</td>
                            <td>'.$row['user_email'].'</td>
                            <td>'.$row['user_last_login_timestamp'].'</td>
                            <td>
                            <a href="profile.php?id='.$row['user_id'].'" title="Profiel weergeven" class="btn btn-primary btn-sm"><span class="fa fa-user"></span></a>
                            </td>
                            </tr>
                            ';
                        }
                        echo '</tbody>';
                    } else {
                        echo '<tr><td colspan="8">Ontbrekende gegevens..</td></tr>';
                    }
                    ?>
                </table>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>
