<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\View\Text;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_DEBUG');
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("debug")) {
    Redirect::permissionError();
    die();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>
<body>
<?php require DIR_ROOT.'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1><?php echo $pageName; ?></h1>
                </div>
            </div>
            <?php Alert::renderFeedbackMessages(); ?>
        </div>
        <hr>
        <div class="container">
            <h2>var_dump($_SESSION)</h2>
            <?php
            var_dump($_SESSION);
            echo '<br>';
            ?>
            <h2>sys_get_temp_dir().'/'</h2>
            <p><?php echo sys_get_temp_dir().'/'; ?></p>
        </div>
                <div class="container">
<iframe width="800" height="600" src="phpinfo.inc.php" frameborder="0"></iframe>

        </div>
    </div>
</main>
<?php include DIR_INCLUDES.'footer.php'; ?>
</body>
</html>
