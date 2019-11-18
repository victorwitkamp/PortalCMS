<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\User\UserPDOReader;

$pageName = 'Gebruikersprofiel weergeven';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
require_once DIR_INCLUDES . 'functions.php';

require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php
$row = UserPDOReader::getProfileById(Request::get('id'));

?>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1>Profiel van: <?= $row->user_name ?></h1>
            </div>
            <table class="table table-striped table-condensed">
                <tr>
                    <th>ID</th><td><?= $row->user_id ?></td>
                </tr>
                <tr>
                    <th>user_emails</th><td><?= $row->user_email ?></td>
                </tr>
                <tr>
                    <th>user_active</th><td><?= $row->user_active ?></td>
                </tr>
                <tr>
                    <th>user_account_type</th><td><?= $row->user_account_type ?></td>
                </tr>
                <tr>
                    <th>user_last_login_timestamp</th><td><?= $row->user_last_login_timestamp ?></td>
                </tr>
            </table>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
