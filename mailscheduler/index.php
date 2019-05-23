<?php
$pageType = 'index';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MAIL_SCHEDULER');
Auth::checkAuthentication();
if (!Permission::hasPrivilege("mail-scheduler")) {
    Redirect::permissionerror();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="generate/" class="btn btn-info float-right">
                        <span class="fa fa-plus"></span> <?php echo Text::get('LABEL_NEW_EMAIL'); ?>
                    </a>
                </div>
            </div>
            <a href="history.php">history</a>
            <hr>
        </div>
        <div class="container">
            <?php
            Util::DisplayMessage();
            PortalCMS_JS_Init_dataTables();
            $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE status = 1 ORDER BY id ASC");
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                echo 'Ontbrekende gegevens..';
            } else {
                include 'table.php';
            }
            ?>
        </div>
    </div>
</main>
<?php require '../includes/footer.php'; ?>
</body>