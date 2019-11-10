<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Modules\Invoices\InvoiceMapper;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('LABEL_CONTRACT_INVOICES_FOR_ID') . ': ' . Request::get('id');
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');
$contract = ContractMapper::getById(Request::get('id'));
if (empty($contract)) {
    Redirect::to('includes/error.php');
}
$pageName = 'Facturen voor ' . $contract->band_naam;
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
            </div>
            <hr>
            <?php
            $invoices = InvoiceMapper::getByContractId((int) Request::get('id'));
            if (!empty($invoices)) {
                include_once DIR_ROOT . 'rental/invoices/invoices_table.php';
                PortalCMS_JS_Init_dataTables();
            } else {
                echo 'Ontbrekende gegevens..';
            }
            ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES . 'footer.php'; ?>
</body>
