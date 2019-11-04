<?php

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');
$contract = ContractMapper::getById($_GET['id']);
if (!$contract) {
    Session::add('feedback_negative', 'Het contract bestaat niet.');
    Redirect::to('includes/error.php');
}
$pageName = 'Contract van ' . $contract['band_naam'];
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>

<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?= $pageName ?></h1>
            </div>
        </div>
        <div class="container">
            <?php require 'inc/buttons.php'; ?>
            <a href="invoices.php?id=<?= $contract['id'] ?>">Facturen bekijken</a>
            <hr>
            <?php require 'inc/view.php'; ?>
            <hr>
            <?php require 'inc/buttons.php'; ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
