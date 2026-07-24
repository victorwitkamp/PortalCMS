<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_HOME');
?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>
    <div class="py-5 bg-body-tertiary">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <img src='<?= $this->e($settings['site_logo'] ?? '') ?>' alt='logo' width='150px' height='150px'/>
                    <?php if ($canEdit) { ?>
                        <br><a href="/Settings/Logo/">Logo wijzigen</a>
                    <?php } ?>
                </div>
                <div class="col-sm-8">
                    <h1><?= $this->e($settings['site_name'] ?? '') ?></h1>
                    <p class="lead"><?= $description ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?= $this->insert(
                ($settings['site_layout'] ?? 'right-sidebar') === 'left-sidebar'
                    ? 'Home::Layout/LeftSidebarLayout'
                    : 'Home::Layout/RightSidebarLayout',
                compact('settings', 'page', 'events', 'canEdit'),
            ) ?>
        </div>
    </div>
<?= $this->end();
