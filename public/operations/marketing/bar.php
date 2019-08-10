<?php

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MARKETING_BAR');
Auth::checkAuthentication();
if (!Auth::checkPrivilege("marketing-bar")) {
    Redirect::permissionError();
    die();
}
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_CSS_calendar();
PortalCMS_JS_headJS();
PortalCMS_JS_calendar(); ?>
</head>

<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h3><?php echo $pageName ?></h3>
            </div>
        </div>

        <div class="container">
            <?php Alert::renderFeedbackMessages(); ?>
            <form method="post" validate=true>
                <div class="form-group form-group-sm row">
                    <div class="col-sm-12">
                        <label class="control-label">content</label>
                        <input type="text" name="content" value="<?php echo $row['content']; ?>" class="form-control input-sm" placeholder="" required>
                    </div>
                </div>
                <hr>
                <div class="form-group form-group-sm row">
                    <input type="submit" name="updateMarketingBar" class="btn btn-sm btn-primary" value="Opslaan">
                    <a href="index.php" class="btn btn-sm btn-danger">Annuleren</a>
                </div>
            </form>
        </div>

    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>
