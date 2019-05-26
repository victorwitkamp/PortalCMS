<?php
$pageName = 'Contract bewerken';
$allowEdit = false;
$pageType = 'edit';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
if (Contract::doesIdExist($_GET['id'])) {
    $row = Contract::getById($_GET['id']);
    $allowEdit = true;
    $pageName = 'Contract van '.$row ['band_naam'].' bewerken';
} else {
    $_SESSION['response'][] = array("status"=>"warning", "message"=>"Geen resultaten voor opgegeven Id.");
}
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
<script src="../includes/js/jquery-simple-validator.nl.js"></script>
<link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">


</head>
<body>
<?php



?>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main role="main" role="main">
    <div class="content">
        <div class="container">
            <div class="row mt-5">
            <h3><?php echo $pageName ?></h3>
            </div>
        </div>
        <div class="container">
        <?php Util::DisplayMessage(); View::renderFeedbackMessages(); ?>
        <?php require 'contract_form.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES.'footer.php'; ?>
</body>
</html>