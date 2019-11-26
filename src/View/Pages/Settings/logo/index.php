<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

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
    <?php Alert::renderFeedbackMessages(); ?>
            <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_LOGO') ?></label>
        <div class="col-8">
            <form method="post" enctype="multipart/form-data">
                <label for="avatar_file">
                Select an avatar image from your hard-disk (will be scaled to 44x44 px, only .jpg
                currently):
                </label>
                <input type="file" name="logo_file" required />
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                <input type="submit" name="uploadLogo" value="Logo uploaden" />
            </form>
        </div>


    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
