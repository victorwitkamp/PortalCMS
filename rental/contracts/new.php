<?php
$pageName = 'Contract toevoegen';
$allowEdit = true;
$pageType = 'new';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("rental-contracts")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus(); ?>
<script >
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
<script src="../includes/js/jquery-simple-validator.nl.js"></script>
<link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">
</head>
<body>
<?php require DIR_INCLUDES."nav.php"; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName; ?></h1>
            </div>
        </div>
        <div class="container">
            <?php
            require "contract_form.php";
            ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES."footer.php"; ?>
</body>
</html>