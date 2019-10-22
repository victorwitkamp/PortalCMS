<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Contracts\ContractMapper;
use PortalCMS\Core\Authentication\Authentication;

$loadData = true;
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("rental-contracts")) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';
$contract = ContractMapper::getById($_GET['id']);
if ($contract) {
    $pageName = 'Contract van '.$contract['band_naam'].' bewerken';
} else {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven Id.");
    Redirect::error();
}
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus(); ?>
<script>
$(function () {
    $('#datetimepicker1').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'nl',
        viewMode: 'years'
    });
});
$(function () {
    $('#datetimepicker2').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'nl',
        viewMode: 'years'
    });
});
$(function () {
    $('#datetimepicker3').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'nl',
        viewMode: 'years'
    });
});
$(function () {
    $('#datetimepicker4').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'nl',
        viewMode: 'years'
    });
});
</script>
<?php //PortalCMS_JS_JQuery_Simple_validator();?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main role="main" role="main">
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h3><?php echo $pageName ?></h3>
            </div>
        </div>
        <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <?php require 'inc/form_edit.php'; ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>