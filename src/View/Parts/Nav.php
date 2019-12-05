<?php
/**
 * Main navigation
 */
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
?>
<div class="navbar bg-light p-0">
    <div class="container">
        <a class="navbar-brand mr-auto mr-lg-0" href="/home"><?= SiteSetting::getStaticSiteSetting('site_name') ?></a>
        <span class="navbar-text p-0">
            <?= Text::get('LABEL_SIGNED_IN_AS') ?><strong> <?= Session::get('user_name') ?></strong><br>
            <a href="/Account"><?= Text::get('TITLE_MY_ACCOUNT') ?></a> | <a href="/logout.php"><i class="fa fa-sign-out-alt"></i><?= Text::get('LABEL_SIGN_OUT') ?></a>
        </span>
    </div>
</div>
<nav class="navbar-expand-sm navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-lg-0">
                <?php require 'navMenu.php'; ?>
            </ul>
        </div>
    </div>
</nav>
<?php