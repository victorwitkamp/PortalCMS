<?php
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_SETTINGS');
Authentication::checkAuthentication();
Authorization::verifyPermission('site-settings'); ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>

<body>
<?= $this->push('main-content') ?>
        <div class="container">
            <form method="post" class="container">
                <div class="row mt-5">
                    <div class="col-sm-8">
                        <h1><?= $pageName ?></h1>
                    </div>
                    <div class="col-sm-4">
                        <input type="submit" name="saveSiteSettings" class="btn btn-success navbar-btn float-right" value="<?= Text::get('LABEL_SUBMIT') ?>">
                    </div>
                </div>
                <?php Alert::renderFeedbackMessages();
                require 'inc/general.php'; ?>
            </form>
        </div>
    </div>
<?= $this->end() ?>
