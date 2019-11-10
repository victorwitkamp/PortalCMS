<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageType = 'history';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MAIL_HISTORY');
Authentication::checkAuthentication();
Authorization::verifyPermission('mail-scheduler');
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_CSS_dataTables();
PortalCMS_JS_headJS();
PortalCMS_JS_dataTables();
?>
</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-8"><h1><?= $pageName ?></h1></div>
                <div class="col-sm-4">
                    <a href="generate/" class="btn btn-info float-right">
                        <span class="fa fa-plus"></span> <?= Text::get('LABEL_NEW_EMAIL') ?>
                    </a>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <?php
            Alert::renderFeedbackMessages();
            PortalCMS_JS_Init_dataTables();
            $result = MailScheduleMapper::getHistory();
            if (!$result) {
                echo 'Ontbrekende gegevens..';
            } else {
                include 'inc/table_messages.php';
            }
            ?>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
