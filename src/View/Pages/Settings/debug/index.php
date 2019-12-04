<?php

use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
$pageName = Text::get('TITLE_DEBUG');
Authentication::checkAuthentication();
Authorization::verifyPermission('debug');
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
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1><?= $pageName ?></h1>
                </div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <hr>
        <div class="container">
            <h2>var_dump($_SESSION)</h2>
            <?php var_dump($_SESSION); ?>
            <br>
            <h2>print_r($_SESSION)</h2>
            <?php print_r($_SESSION); ?>
            <br>
            <h2>sys_get_temp_dir().'/'</h2>
            <p><?= sys_get_temp_dir() . '/' ?></p>
        </div>
                <div class="container">
<iframe width="800" height="600" src="phpinfo.inc.php" frameborder="0"></iframe>

        </div>
    </div>
</main>
<?php require DIR_VIEW . 'Parts/Footer.php'; ?>
</body>
</html>
