<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Redirect;
use PortalCMS\Core\Text;
use PortalCMS\Models\Invoice;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR_ID').': '.$_GET['id'];
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("rental-contracts")) {
    Redirect::permissionError();
    die();
}
$contract = ContractMapper::getById($_GET['id']);
if (!$contract) {
    Redirect::error();
}
$pageName = "Facturen voor ".$contract['band_naam'];
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
            </div>

        <hr>
        <?php
        $invoices = Invoice::getByContractId($_GET['id']);
        if ($invoices) {
            include '../invoices/invoices_table.php';
            PortalCMS_JS_Init_dataTables();
        } else {
            echo 'Ontbrekende gegevens..';
        }
        ?>

        </div>
    </div>
</main>
<?php require DIR_INCLUDES."footer.php"; ?>
</body>
