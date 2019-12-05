<?php

use PortalCMS\Core\Security\Authentication\Authentication;

$pageName = 'Leden importeren (CSV)';

Authentication::checkAuthentication();

require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>

</head>
<body>
<?php require DIR_VIEW . 'Parts/Nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12"><h1><?= $pageName ?></h1></div>
            </div>
        </div>
        <div class="container">
            <form method="post" action="importer.php" enctype="multipart/form-data">
                <input type="text" name="jaarlidmaatschap" value="2019"/>
                <input type="file" name="file"/>
                <input type="submit" name="submit_file" value="Submit"/>
            </form>
        </div>
    </div>
</main>
</body>
</html>