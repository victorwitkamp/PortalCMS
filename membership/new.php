<?php
$pageName = 'Lid toevoegen';
$pageType = 'new';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("membership")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
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
<?php require '../includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName ?></h1>
            </div>
        </div>
        <div class="container">
            <?php require "form.php"; ?>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>