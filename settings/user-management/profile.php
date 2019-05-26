<?php
$pageName = 'Profiel';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("user-management")) {
    Redirect::permissionerror();
    die();
}
$row = User::getProfileById($_GET['id']);
if (!$row) {
    $_SESSION['response'][] = array("status"=>"error", "message"=>"De gebruiker bestaat niet.");
    Redirect::Error();
}
require DIR_ROOT.'includes/functions.php';
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php require DIR_ROOT.'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1>Profiel van: <?php echo $row['user_name']; ?></h1>
            </div>
            <?php Util::DisplayMessage(); View::renderFeedbackMessages();
            require 'profile_buttons.php';
            ?>
        <hr><?php
            require 'profile_table.php'; ?>
            <!-- <hr> -->
            <?php //require 'profile_buttons.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_ROOT.'includes/footer.php'; ?>
</body>
</html>