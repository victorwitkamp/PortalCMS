<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authorization\RolesPDOReader;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_ROLE_MANAGEMENT');
Authentication::checkAuthentication();
Authorization::verifyPermission('role-management');
require DIR_ROOT . 'includes/functions.php';
require DIR_ROOT . 'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_ROOT . 'includes/nav.php'; ?>

<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
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

                    $Roles = RolesPDOReader::getRoles();
                    if (!empty($Roles)) { ?>
                        <tbody>
                        <?php foreach ($Roles as $Role) { ?>
                            <tr>
                                <td><?= $Role->role_id ?></td>
                                <td><?= $Role->role_name ?></td>
                                <td>
                                    <a href="role.php?role_id=<?= $Role->role_id ?>" title="Rol beheren" class="btn btn-primary btn-sm">
                                        <span class="fa fa-cog"></span>
                                    </a>
                                    <form method="post">
                                        <input type="hidden" name="role_id" value="<?= $Role->role_id ?>">
                                        <button type="submit" name="deleterole" class="btn btn-danger btn-sm" onclick="return confirm('Weet u zeker dat u de rol <?= $Role->role_name ?> wilt verwijderen?')">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8">Ontbrekende gegevens..</td>
                        </tr>
                    <?php } ?>
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
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
