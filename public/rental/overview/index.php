<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

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
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4"><a href="new.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> <?= Text::get('LABEL_ADD') ?></a></div>
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
