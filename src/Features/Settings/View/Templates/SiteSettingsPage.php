<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_SITE_SETTINGS'); ?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <form method="post" action="/Settings/SiteSettings" class="container">
            <div class="row mt-5">
                <div class="col-sm-8">
                    <h1><?= $pageName ?></h1>
                </div>
                <div class="col-sm-4">
                    <input type="submit" class="btn btn-success navbar-btn float-end"
                           value="<?= Text::get('LABEL_SUBMIT') ?>">
                </div>
            </div>
            <?php echo $this->insert('View::Partials/FlashMessages', compact('flashMessages')); ?>
            <?= $this->insert('Settings::Partials/SiteSettingsForm', [ 'settings' => $settings ]) ?>
        </form>
    </div>

<?= $this->end();
