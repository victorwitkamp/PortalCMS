<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Modules\Members\MemberModel;

$pageName = 'Profiel';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("membership")) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';
$row = MemberModel::getMemberById($_GET['id']);
$pageName = 'Lidmaatschap van '.$row['voornaam'].' '.$row['achternaam'];

require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName ?></h1>
            </div>
        </div>
        <div class="container">
            <?php require 'profile_buttons.php'; ?>
            <hr>
            <?php require 'profile_table.php'; ?>
            <hr>
            <?php require 'profile_buttons.php'; ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>
