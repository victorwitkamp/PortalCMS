<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

// Authentication::checkAuthentication();
$pageName = Text::get('TITLE_MY_ACCOUNT');
// require $_SERVER['DOCUMENT_ROOT'] . '/login/ext/fb/config.php';
// $helper = $fb->getRedirectLoginHelper();
// $permissions = ['email'];
// $loginUrl = $helper->getLoginUrl(Config::get('FB_ASSIGN_URL'), $permissions);
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>
    <!-- <script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css"> -->
<?= $this->end() ?>
<?= $this->push('main-content') ?>
    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?php Alert::renderFeedbackMessages(); ?>
        <?php require DIR_VIEW . 'Pages/Account/inc/accountDetails.inc.php'; ?>
        <?php require DIR_VIEW . 'Pages/Account/inc/changePassword.inc.php'; ?>
        <?php require DIR_VIEW . 'Pages/Account/inc/changeUsername.inc.php'; ?>
    </div>
<?= $this->end() ?>
