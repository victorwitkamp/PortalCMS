<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_SETTINGS'); ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>

        <form method="post">
            <div class="container">
                <div class="row mt-5">
                    <div class="col-sm-8">
                        <h1><?= $pageName ?></h1>
                    </div>
                    <div class="col-sm-4">
                        <input type="submit" name="saveSiteSettings" class="btn btn-outline-success navbar-btn float-right" value="<?= Text::get('LABEL_SUBMIT') ?>">
                    </div>
                </div>
                <hr>
                <?php Alert::renderFeedbackMessages(); ?>
            </div>
            <div class="container">
                <?php require __DIR__ . '/inc/SiteSettingsGeneral.php'; ?>
            </div>
        </form>

<?= $this->end();
