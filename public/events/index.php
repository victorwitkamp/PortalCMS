<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'].'/Init.php';
$pageName = Text::get('TITLE_EVENTS');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege('events')) {
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
                <div class="col-sm-8">
                    <h1><?php echo $pageName ?></h1>
                </div>
                <div class="col-sm-4">
                    <a href="add.php" class="btn btn-info float-right"><span class="fa fa-plus"></span> <?php echo Text::get('LABEL_ADD'); ?></a>
                </div>
            </div>
        </div>
        <div class="container">
            <?php Alert::renderFeedbackMessages(); ?>
            <hr>
            <div class="row justify-content-center">

<div class="col-sm-12">

    <div id="calendar"></div>
</div>

</div>
        </div>
    </div>
</main>

<?php include DIR_INCLUDES.'footer.php'; ?>

<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title"><?php echo Text::get('LABEL_EVENT_DETAILS'); ?></h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only"><?php echo Text::get('CLOSE'); ?></span>
                </button>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo Text::get('LABEL_CLOSE'); ?></button>
                <a class="btn btn-primary" id="eventUrl" role="button"><i class="far fa-edit"></i> <?php echo Text::get('LABEL_EDIT'); ?></a>
                <form method="post">
                    <input name="id" type="hidden" id="deleteUrl" value="">
                    <button name="deleteEvent" type="submit" class="btn btn-danger" ><i class="far fa-trash-alt"></i> <?php echo Text::get('LABEL_DELETE'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
