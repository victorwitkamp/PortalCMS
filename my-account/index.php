<?php

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
$pageName = Text::get('TITLE_MY_ACCOUNT');

require $_SERVER["DOCUMENT_ROOT"]."/login/ext/fb/config.php";
    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl(FB_ASSIGN_URL, $permissions);

require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
<?php PortalCMS_JS_JQuery_Simple_validator(); ?>
</head>
<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName ?></h1>
            </div>
        <hr>
            <?php Alert::renderFeedbackMessages(); ?>
            <?php require DIR_ROOT.'my-account/inc/accountDetails.inc.php'; ?>
            <hr>
            <?php require DIR_ROOT.'my-account/inc/changePassword.inc.php'; ?>
            <hr>
            <?php require DIR_ROOT.'my-account/inc/changeUsername.inc.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES.'footer.php'; ?>
</body>
</html>