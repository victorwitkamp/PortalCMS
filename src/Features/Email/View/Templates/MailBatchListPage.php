<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageType = 'index';
$pageName = Text::get('TITLE_MAIL_BATCHES');
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>

    <link rel="stylesheet" type="text/css" href="/dist/merged/dataTables.min.css">
    <script src="/dist/merged/dataTables.min.js"></script>
    <script src="/includes/js/init.datatables.js" class="init"></script>

<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1><?= $pageName ?></h1>
            </div>
            <div class="col-sm-4">
                <a href="/Email/Generate/" class="btn btn-info float-end">
                    <span class="fa fa-plus"></span> <?= Text::get('LABEL_NEW_EMAIL') ?>
                </a>
            </div>
        </div>
        <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="Batches">Batches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Messages">Messages</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <?php
                if (!empty($batches)) {
                    echo '<p>Aantal: ' . count($batches) . '</p>';
                    echo $this->insert(
                        'Email::Partials/MailBatchTable',
                        compact('batches', 'messageCounts', 'pageType'),
                    );
                } else {
                    echo Text::get('LABEL_NOT_FOUND');
                }
                ?>
            </div>
        </div>
    </div>

<?= $this->end();
