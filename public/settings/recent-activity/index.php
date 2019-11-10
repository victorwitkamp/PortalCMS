<?php

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_RECENT_ACTIVITY');
Authentication::checkAuthentication();
Authorization::verifyPermission('recent-activity');
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
                <div class="col-sm-12">
                    <h1><?= $pageName ?></h1>
                </div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <div class="container">
            <table class="table table-sm table-striped table-hover table-dark">
                <thead>
                    <th>CreationDate</th>
                    <th>activity_id</th>
                    <th>user_id</th>
                    <th>user_name</th>
                    <th>ip_address</th>
                    <th>activity</th>
                </thead>
                <?php $Activities = Activity::load();
                foreach ($Activities as $Activity) {
                    ?>
                        <tr>
                            <td><?= $Activity['CreationDate'] ?></td>
                            <td><?= $Activity['id'] ?></td>
                            <td><?= $Activity['user_id'] ?></td>
                            <td><?= $Activity['user_name'] ?></td>
                            <td><?= $Activity['ip_address'] ?></td>

                            <td><?= $Activity['activity'] ?></td>
                            <td><?= $Activity['details'] ?></td>

                        </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
