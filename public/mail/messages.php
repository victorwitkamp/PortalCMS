<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;

$pageType = 'index';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MAIL_SCHEDULER');
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
                    <div class="col-sm-8">
                        <h1><?= $pageName ?></h1>
                    </div>
                    <div class="col-sm-4">
                        <a href="generate/" class="btn btn-info float-right">
                            <span class="fa fa-plus"></span> <?= Text::get('LABEL_NEW_EMAIL') ?>
                        </a>
                    </div>
                </div>

                <?php
                Alert::renderFeedbackMessages();
                ?>

            </div>
            <div class="container">
                <nav>
                    <div class="nav nav-tabs mb-3 bg-secondary" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-home-tab" href="index.php" role="tab">Batches</a>
                        <a class="nav-item nav-link active" id="nav-profile-tab" href="messages.php" role="tab">Messages</a>
                    </div>
                </nav>
                </div>
                <div class="container">
                <?php
                PortalCMS_JS_Init_dataTables();

                if (!empty($_GET['batch_id'])) {
                    $result = MailScheduleMapper::getByBatchId($_GET['batch_id']);
                } else {
                    $result = MailScheduleMapper::getAll();
                }
                $mailcount = count($result);
                if (!$result) {
                    echo 'Ontbrekende gegevens..';
                } else {
                    if (!empty($_GET['batch_id'])) {
                        echo '<h3>Berichten van batch ' . $_GET['batch_id'] . '</h3>';
                    } else {
                        echo '<h3>Alle berichten</h3>';
                    }
                    echo '<p>Aantal: ' . $mailcount . '</p>';
                    include 'inc/table_messages.php';
                }
                ?>
            </div>
        </div>
    </main>
    <?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
