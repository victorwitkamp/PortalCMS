<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Config\SiteSetting;

$pageName = 'Account activeren';

require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';
if (!isset($_SESSION)) {
    session_start();
}

require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();

PortalCMS_CSS_floatingLabels();
PortalCMS_JS_headJS();

?>
</head>
<body class="bg">
    <header>
        <div class="navbar navbar-dark bg-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/login"><span class="fa fa-arrow-left"></span> Inloggen</a>
                </li>
            </ul>
        </div>
    </header>
    <main>
        <div class="container col-md-6 offset-md-3 mt-5">
            <form method="post" class="form-signin shadow" validate=true>
                <div class="card">
                    <div class="card-header text-center">
                        <img src='<?= SiteSetting::getStaticSiteSetting('site_logo') ?>' alt='<?= SiteSetting::getStaticSiteSetting('site_name') ?>' width='200px' height='200px'/>
                        <h1 class="h3 mb-3 font-weight-normal"><?= SiteSetting::getStaticSiteSetting('site_name') ?></h2>
                        <?php Alert::renderFeedbackMessages(); ?>
                    </div>
                    <div class="card-body">
                        <h2 class="h3 mb-3 font-weight-normal "><?= $pageName ?></h3>
                        <div class="form-label-group">
                            <input type="email" name="email" title="The domain portion of the email address is invalid (the portion after the @)." pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$"  id="inputEmail" class="form-control" placeholder="E-mailadres" autofocus required>
                            <label for="inputEmail">E-mailadres</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" minlength="32" maxlength="32" name="code" id="inputCode" class="form-control" placeholder="code" required>
                            <label for="inputCode">Activatiecode</label>
                        </div>
                        <input type="submit" name="activateSubmit" value="Activeren" class="btn btn-secondary mb-sm-2">
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
