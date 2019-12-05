<?php
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_HOME');
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('main-content') ?>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <img src='<?= SiteSetting::getStaticSiteSetting('site_logo') ?>' alt='logo' width='120px' height='120px' />
                        <?php if (Authorization::hasPermission('site-settings')) { ?>
                            <br><a href="/Settings/Logo/">Logo wijzigen</a>
                        <?php } ?>

                    </div>
                    <div class="col-sm-9">
                        <h1><?= SiteSetting::getStaticSiteSetting('site_name') ?></h1>
                        <p class="lead">
                        <?php
                        if (SiteSetting::getStaticSiteSetting('site_description_type') === '1') {
                            echo SiteSetting::getStaticSiteSetting('site_description');
                        }
                        require 'slogan.php';
                        ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php
                $layout = SiteSetting::getStaticSiteSetting('site_layout');
                require 'layouts/' . $layout . '.php';
                ?>
            </div>
        </div>
<?= $this->end() ?>
