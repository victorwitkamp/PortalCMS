<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_PROFILE');
Authentication::checkAuthentication();
Authorization::verifyPermission('user-management');
$row = UserPDOReader::getProfileById($_GET['id']);
if (empty($row)) {
    Session::add('feedback_negative', 'De gebruiker bestaat niet.');
    Redirect::to('includes/error.php');
} else {
    $pageName = Text::get('TITLE_PROFILE') . $row->user_name;
}
require DIR_ROOT . 'includes/functions.php';
require DIR_ROOT . 'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php require DIR_ROOT . 'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1>Profiel van: <?= $row->user_name ?></h1>
            </div>
            <?php Alert::renderFeedbackMessages();
            require 'profile_buttons.php';
            ?>
            <hr>
        </div>
        <div class="container-fluid">
            <?php
            require 'profile_table.php'; ?>
            <!-- <hr> -->
            <?php //require 'profile_buttons.php';?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
