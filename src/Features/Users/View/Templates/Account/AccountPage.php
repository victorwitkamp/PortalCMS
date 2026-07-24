<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_MY_ACCOUNT');
//require DIR_ROOT . 'login/ext/fb/config.php';
//$helper = $fb->getRedirectLoginHelper();
//$permissions = ;
//$loginUrl = $helper->getLoginUrl(Config::get('FB_ASSIGN_URL'), [ 'email' ]);
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>
    <script src="/includes/js/pass_req.js"></script>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <?= $this->insert('View::Partials/FlashMessages', compact('flashMessages')) ?>
        <?= $this->insert('Users::Account/Partials/AccountDetails', compact('user', 'roles')) ?>
        <?= $this->insert('Users::Account/Partials/ChangePasswordForm', compact('user', 'changePasswordCsrfToken')) ?>
        <?= $this->insert('Users::Account/Partials/ChangeUsernameForm', compact('changeUsernameCsrfToken')) ?>
    </div>

<?= $this->end();
