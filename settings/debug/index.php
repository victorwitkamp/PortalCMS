<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_DEBUG');
Auth::checkAuthentication();
if (!Permission::hasPrivilege("debug")) {
    Redirect::permissionerror();
    die();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_ROOT.'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1><?php echo $pageName; ?></h1>
                </div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <hr>
        <div class="container">
            <h2>var_dump($_SESSION)</h2>
            <?php var_dump($_SESSION); echo '<br>'; ?>
        </div>
                <div class="container">
<iframe width="800" height="600" src="phpinfo.inc.php" frameborder="0"></iframe>

        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>