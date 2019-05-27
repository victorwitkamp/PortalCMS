<?php
$pageName = 'Wijzigen';
$pageType = 'edit';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
if (Member::doesMemberIdExist($_GET['id'])) {
    $row = Member::getMemberById($_GET['id']);
    $allowEdit = true;
    $pageName = 'Lidmaatschap van '.$row ['voornaam'].' '.$row ['achternaam'].' bewerken';
} else {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven Id.");
}
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_tempusdominus();
PortalCMS_JS_headJS();
PortalCMS_JS_tempusdominus();
PortalCMS_JS_JQuery_Simple_validator();
PortalCMS_JS_Datepicker_membership();
?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main role="main" role="main">
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName ?></h1>
            </div>
        </div>
        <hr>
        <div class="container">
            <?php require 'form.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES.'footer.php'; ?>
</body>
</html>