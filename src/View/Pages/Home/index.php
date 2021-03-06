<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_HOME');
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('main-content') ?>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <img src='<?= SiteSetting::get('site_logo') ?>' alt='logo' width='150px' height='150px'/>
                    <?php if (Authorization::hasPermission('site-settings')) { ?>
                        <br><a href="/Settings/Logo/">Logo wijzigen</a>
                    <?php } ?>
                </div>
                <div class="col-sm-8">
                    <h1><?= SiteSetting::get('site_name') ?></h1>
                    <p class="lead"><?php require __DIR__ . '/inc/Description.php'; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $layout = SiteSetting::get('site_layout');
            if ($layout === 'left-sidebar') {
                require __DIR__ . '/layouts/left-sidebar.php';
            }
            if ($layout === 'right-sidebar') {
                require __DIR__ . '/layouts/right-sidebar.php';
            }
            ?>
        </div>
    </div>
<?= $this->end();
