<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Alert;
use PortalCMS\Core\Redirect;
use PortalCMS\Core\Text;
use PortalCMS\Core\View;

$pageType = 'index';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_MAIL_SCHEDULER');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("mail-scheduler")) {
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
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?php echo $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="generate/" class="btn btn-info float-right">
                        <span class="fa fa-plus"></span> <?php echo Text::get('LABEL_NEW_EMAIL'); ?>
                    </a>
                </div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <div class="container">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" href="index.php" role="tab">Batches</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" href="messages.php" role="tab">Messages</a>
                </div>
            </nav>
            <?php
            PortalCMS_JS_Init_dataTables();
            $batches = MailBatch::getAll();
            $batchcount = count($batches);
            echo '<h2>Batches</h2><p>Aantal: '.$batchcount.'</p>';
            require 'inc/table_batches.php';
            ?>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
