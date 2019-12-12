<?php
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_SETTINGS'); ?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
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
                require 'inc/SiteSettingsGeneral.php'; ?>
            </form>
        </div>
    </div>

<?= $this->end();
