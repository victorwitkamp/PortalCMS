<?php

use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageType = 'index';
$pageName = Text::get('TITLE_MAIL_BATCHES');
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
        <?php Alert::renderFeedbackMessages(); ?>
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
            //PortalCMS_JS_Init_dataTables();
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
    </div>

<?= $this->end();
