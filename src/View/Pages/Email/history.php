<?php

use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageType = 'history';
$pageName = Text::get('TITLE_MAIL_HISTORY');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/includes/js/init.datatables.js" class="init"></script>

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
</div>
<div class="container">
    <?php
    Alert::renderFeedbackMessages();
    $result = MailScheduleMapper::getHistory();
    if ($result) {
        include __DIR__ .'inc/table_messages.php';
    } else {
        echo 'Geen berichten gevonden.';
    }
    ?>
</div>

<?= $this->end();
