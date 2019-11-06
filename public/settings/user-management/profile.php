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
$user = UserPDOReader::getProfileById($_GET['id']);
if (empty($user)) {
    Session::add('feedback_negative', 'De gebruiker bestaat niet.');
    Redirect::to('includes/error.php');
} else {
    $pageName = Text::get('TITLE_PROFILE') . $user->user_name;
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
                <h1>Profiel van: <?= $user->user_name ?></h1>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post" action="index.php">
                <a href="index.php" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
                <input type="hidden" name="user_id" value="<?= $user->user_id ?>">
                <button type="submit" name="deleteuser" onclick="return confirm('Weet je zeker dat je <?= $user->user_name ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
            </form>
            <hr>
        </div>
        <div class="container-fluid">
            <?php require 'profile_table.php'; ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
