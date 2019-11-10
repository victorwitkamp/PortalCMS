<?php
/**
 * Main navigation
 */

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

?>
<header>
<!--    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">-->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">

    <div class="container-fluid">
            <a class="navbar-brand" href="/home"><?= SiteSetting::getStaticSiteSetting('site_name') ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-lg-0">
                    <?php
                    // Navigation items
                    require 'navMenu.php'; ?>
                </ul>

                <span class="navbar-text">
                    <?= Text::get('LABEL_SIGNED_IN_AS') ?><strong> <?= Session::get('user_name') ?></strong> | <a href="/my-account"><?= Text::get('TITLE_MY_ACCOUNT') ?></a> | <a href="/logout.php"><i class="fa fa-sign-out-alt"></i><?= Text::get('LABEL_SIGN_OUT') ?></a>
                </span>
            </div>
        </div>
    </nav>
</header>
