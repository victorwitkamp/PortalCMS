<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_MY_ACCOUNT');
require DIR_ROOT . 'login/ext/fb/config.php';
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(Config::get('FB_ASSIGN_URL'), $permissions);
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
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

<?= $this->end()
