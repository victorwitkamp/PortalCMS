<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageType = 'index';
$pageName = Text::get('TITLE_MAIL_BATCHES');
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
<!--    <script src="/includes/js/init.datatables.js" class="init"></script>-->

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4">
                <a href="/Email/Generate/" class="btn btn-info float-right">
                    <span class="fa fa-plus"></span> <?= Text::get('LABEL_NEW_EMAIL') ?>
                </a>
            </div>
        </div>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <nav>
            <div class="nav nav-tabs mb-3 bg-secondary" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" href="Batches" role="tab">Batches</a>
                <a class="nav-item nav-link" id="nav-profile-tab" href="Messages" role="tab">Messages</a>
            </div>
        </nav>
    </div>
    <div class="container-fluid">
        <?php
        $batches = MailBatch::getAll();
        if (!empty($batches)) {
            include __DIR__ . '/inc/table_batches.php';
        } else {
            echo Text::get('LABEL_NOT_FOUND');
        }
        ?>
    </div>

<?= $this->end();
