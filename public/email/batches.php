<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageType = 'index';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_MAIL_BATCHES');
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
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
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
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <div class="container">
          <div class="card">
            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="batches.php">Batches</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="messages.php">Messages</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
                      <?php
                        PortalCMS_JS_Init_dataTables();

                        $batches = MailBatch::getAll();
                        if (!empty($batches)) {
                            echo '<p>Aantal: ' . count($batches) . '</p>';
                            include 'inc/table_batches.php';
                        } else {
                            echo Text::get('LABEL_NOT_FOUND');
                        }

                        ?>
            </div>
        </div>

            <!-- <nav>
                <div class="nav nav-tabs mb-3 bg-secondary" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" href="index.php" role="tab"><?php //Text::get('LABEL_BATCHES') ?></a>
                    <a class="nav-item nav-link" id="nav-profile-tab" href="messages.php" role="tab"><?php //Text::get('LABEL_MESSAGES') ?></a>
                </div>
            </nav> -->

        </div>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
</body>
