<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_OVERVIEW');
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');
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
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4"><a href="new.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> <?php echo Text::get('LABEL_ADD'); ?></a></div>
            </div>
            <hr>
            <?php
            Alert::renderFeedbackMessages();
            $stmt = DB::conn()->query('SELECT count(id) as NumberOfContracts FROM contracts');
            $row = $stmt->fetchColumn();
            echo 'Totaal aantal contracten: ' . $row . '<br>';
            $stmt = DB::conn()->query('SELECT count(id) as NumberOfInvoices FROM invoices');
            $row = $stmt->fetchColumn();
            echo 'Totaal aantal facturen: ' . $row;
            ?>

        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
