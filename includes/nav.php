<?php
/**
 * Main navigation
 */
?>
<header>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/home/index.php"><?php echo SiteSetting::getStaticSiteSetting('site_name'); ?></a>
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
                    <?php echo Text::get('LABEL_SIGNED_IN_AS'); ?><strong> <?php echo Session::get('user_name'); ?></strong><br>
                    <a href="/my-account/index.php"><?php echo Text::get('TITLE_MY_ACCOUNT'); ?></a>
                    <a href="/logout.php"><span class="fa fa-sign-out-alt"></span><?php echo Text::get('LABEL_SIGN_OUT'); ?></a>
                </span>
            </div>
        </div>
    </nav>
</header>