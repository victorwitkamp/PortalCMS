<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;

$pageName = 'Contract toevoegen';
$loadData = false;
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('rental-contracts');
require_once DIR_INCLUDES . 'functions.php';
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
        viewMode: 'years',
        defaultDate: '01-01-1990'
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
    <?php require DIR_INCLUDES . 'nav.php'; ?>
    <main>
        <div class="content">
            <div class="container">
                <div class="row mt-5">
                    <h1><?php echo $pageName; ?></h1>
                </div>
            </div>
            <div class="container">
                <?php require 'inc/form_new.php'; ?>
            </div>
        </div>
    </main>
    <?php require DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
