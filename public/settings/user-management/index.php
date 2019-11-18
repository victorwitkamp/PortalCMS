<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_USER_MANAGEMENT');
Authentication::checkAuthentication();
Authorization::verifyPermission('user-management');
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
                <!-- <div class="col-sm-4"><a href="#" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div> -->
            </div>
            <?php
            Alert::renderFeedbackMessages(); ?>
            <hr>
                <table class="table table-sm table-striped table-hover table-dark">
                    <thead class="thead-dark">
                        <tr>
                            <th><?= Text::get('LABEL_USER_ID') ?></th>
                            <th><?= Text::get('LABEL_USER_NAME') ?></th>
                            <th><?= Text::get('LABEL_USER_EMAIL') ?></th>
                            <th><?= Text::get('LABEL_USER_LAST_LOGIN_TIMESTAMP') ?></th>
                            <th>Profiel</th>
                        </tr>
                    </thead>
                    <?php

                    $users = UserPDOReader::getUsers();
                    if (!empty($users)) { ?>
                        <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                            <td><?= $user->user_id ?></td>
                            <td><?= $user->user_name ?></td>
                            <td><?= $user->user_email ?></td>
                            <td><?= $user->user_last_login_timestamp ?></td>
                            <td><a href="profile.php?id=<?= $user->user_id ?>" title="Profiel weergeven" class="btn btn-primary btn-sm"><span class="fa fa-user"></span></a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    <?php } else { ?>
                        <tr><td colspan="8">Ontbrekende gegevens..</td></tr>
                    <?php } ?>
                </table>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
