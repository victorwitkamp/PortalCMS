<?php

use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';

$pageName = Text::get('TITLE_SITE_SETTINGS');

Authentication::checkAuthentication();

Authorization::verifyPermission('site-settings');

require DIR_ROOT . 'includes/functions.php';

require DIR_ROOT . 'includes/head.php';

displayHeadCSS();

PortalCMS_JS_headJS();

?>



</head>

<body>

<?php require DIR_ROOT . 'includes/nav.php'; ?>



<main>

    <div class="content">

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

                require 'inc/general.php'; ?>

                <hr>

                <?php require 'inc/widgets.php'; ?>

                <hr>

                <?php require 'inc/mailserver.php'; ?>

            </form>

        </div>

    </div>

</main>

<?php include DIR_INCLUDES . 'footer.php'; ?>

</body>

</html>

