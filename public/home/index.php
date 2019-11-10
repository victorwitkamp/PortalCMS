<?php
/**
 * The homepage.
 *
 * Description of the homepage
 */

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_HOME');
Authentication::checkAuthentication();
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>

<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <img src='<?= SiteSetting::getStaticSiteSetting('site_logo') ?>' alt='logo' width='120px' height='120px' />
                        <?php if (Authorization::hasPermission('site-settings')) { ?>
                            <br><a href="/settings/logo/">Logo wijzigen</a>
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
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>

</html>
