<?php
$pageName = 'Gebruikersprofiel weergeven';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';

require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>
<?php
$row = User::getProfileById($_GET['id']);

?>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1>Profiel van: <?php echo $row['user_name']; ?></h1>
            </div>
            <?php require 'profile_table.php'; ?>
        </div>
    </div>
</main>
<?php View::renderFooter(); ?>
</body>
</html>