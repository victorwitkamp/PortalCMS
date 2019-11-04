<?php

use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Members\MemberModel;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

$pageName = 'Wijzigen';
$pageType = 'edit';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('membership');
require_once DIR_INCLUDES . 'functions.php';
if (MemberModel::doesMemberIdExist($_GET['id'])) {
    $row = MemberModel::getMemberById($_GET['id']);
    $allowEdit = true;
    $pageName = 'Lidmaatschap van ' . $row->voornaam . ' ' . $row->achternaam . ' bewerken';
} else {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven Id.');
}
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus();
PortalCMS_JS_JQuery_Simple_validator();
PortalCMS_JS_Datepicker_membership();
?>
</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main role="main" role="main">
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <hr>
        <div class="container">
            <?php require 'form.php'; ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
