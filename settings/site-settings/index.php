<?php 
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_SITE_SETTINGS');
Auth::checkAuthentication();
// Auth::checkAdminAuthentication();
if (!Permission::hasPrivilege("site-settings")) {
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
        <form method="post" class="container">
            <div class="row mt-5">
                <div class="col-sm-8">
                    <h1><?php echo $pageName; ?></h1>
                </div>
                <div class="col-sm-4">
                    <input type="submit" name="saveSiteSettings" class="btn btn-success navbar-btn float-right" value="<?php echo Text::get('LABEL_SUBMIT'); ?>">
                </div>
            </div>
            <?php Util::DisplayMessage();?>
            <?php require 'form.php'; ?>
        </form>
    </div>
</main>
<?php require DIR_ROOT.'includes/footer.php'; ?>
</body>
</html>