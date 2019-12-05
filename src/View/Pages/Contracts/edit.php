<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Alert;
use PortalCMS\Modules\Contracts\ContractMapper;

$loadData = true;

Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');

$contract = ContractMapper::getById(Request::get('id'));
if (empty($contract)) {
    Session::add('feedback_negative', 'Geen resultaten voor opgegeven Id.');
    Redirect::to('Error/Error');
}
$pageName = 'Contract van ' . $contract->band_naam . ' bewerken';
require_once DIR_INCLUDES . 'head.php';
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
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
<main role="main" role="main">
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h3><?= $pageName ?></h3>
            </div>
        </div>
        <div class="container">
        <?php Alert::renderFeedbackMessages(); ?>
        <?php require 'inc/form_edit.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
</body>
</html>
