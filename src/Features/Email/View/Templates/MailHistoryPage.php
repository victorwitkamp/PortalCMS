<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageType = 'history';
$pageName = Text::get('TITLE_MAIL_HISTORY');
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
        <hr>
    </div>
    <div class="container">
        <?php
        echo $this->insert('View::Partials/FlashMessages', compact('flashMessages'));
        if ($mails) {
            echo $this->insert(
                'Email::Partials/ScheduledMailTable',
                compact('mails', 'pageType'),
            );
        } else {
            echo 'Geen berichten gevonden.';
        }
        ?>
    </div>

<?= $this->end();
