<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MEMBERS');
$year = Request::get('year');
Authentication::checkAuthentication();
Authorization::verifyPermission('membership');
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>
</head>
<body>

<?php require DIR_INCLUDES . 'nav.php'; ?>

<main >

    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="import/" class="btn btn-info float-right"><span class="fa fa-plus"></span> <?php echo Text::get('LABEL_IMPORT'); ?></a>
                    <a href="new.php" class="btn btn-success float-right"><span class="fa fa-plus"></span> <?php echo Text::get('LABEL_ADD'); ?></a>
                </div>
            </div>
            <p><?php echo Text::get('YEAR') . ': ' . $year; ?></p>
        <hr>
        <?php


        Alert::renderFeedbackMessages();
        PortalCMS_JS_Init_dataTables();
        $stmt = DB::conn()->query('SELECT * FROM members ORDER BY voornaam ');
        if ($stmt->rowCount() === 0) {
            echo 'Ontbrekende gegevens..';
        } else {
            include 'members_table.php';
        }
        ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
