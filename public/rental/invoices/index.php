<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\View\Text;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_INVOICES');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("rental-invoices")) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4"><a href="add.php" class="btn btn-success navbar-btn float-right"><span class="fa fa-plus"></span> Toevoegen</a></div>
            </div>
            <hr>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>

        <div class="container-fluid">
            <?php
            $invoices = InvoiceMapper::getAll();
            if ($invoices) {
                include 'invoices_table.php';
                PortalCMS_JS_Init_dataTables();
            } else {
                echo 'Geen facturen gevonden.';
            }
            ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES."footer.php"; ?>
</body>
