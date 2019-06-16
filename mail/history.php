<?php
$pageType = 'history';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MAIL_HISTORY');
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-scheduler")) {
    Redirect::permissionError();
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
            <hr>
        </div>
        <div class="container">
            <?php
            Alert::renderFeedbackMessages();
            PortalCMS_JS_Init_dataTables();
            // $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ASC");
            $result = MailSchedule::getHistory();
            if (!$result) {
                echo 'Ontbrekende gegevens..';
            } else {
                include 'inc/table.php';
            }
            ?>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>