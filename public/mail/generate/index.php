<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Authorization\Authorization;
use PortalCMS\Core\Authentication\Authentication;

$pageName = 'Nieuw bericht';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
Authentication::checkAuthentication();
Authorization::verifyPermission('mail-scheduler');
require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>

</head>
<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">

        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12"><h1><?= $pageName ?></h1></div>
            </div>
        </div>
        <hr>
        <div class="container">
            <?php
            Alert::renderFeedbackMessages();
            ?>
            <h2>Nieuw bericht met template</h2>
            <p>Aan wie wil je een e-mail versturen?<br>
            <a href="templatebased/member.php">Lid</a><br>
            <!-- <a href="user.php">Gebruiker</a> -->
        </div>
    </div>
</main>
</body>
</html>
