<?php

use PortalCMS\Core\View\Alert;
use PortalCMS\Core\Config\SiteSetting;

$pageName = 'Wachtwoord vergeten';
require $_SERVER['DOCUMENT_ROOT'] . '/Init.php';

require_once DIR_INCLUDES . 'functions.php';
require_once DIR_INCLUDES . 'head.php';
displayHeadCSS();

PortalCMS_CSS_floatingLabels();
PortalCMS_JS_headJS();
?>
<?php PortalCMS_JS_JQuery_Simple_validator(); ?>
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
                        <img src='<?php echo SiteSetting::getStaticSiteSetting('site_logo'); ?>' alt='<?php echo SiteSetting::getStaticSiteSetting('site_name'); ?>' width='200px' height='200px'/>
                        <h1 class="h3 mb-3 font-weight-normal"><?php echo SiteSetting::getStaticSiteSetting('site_name'); ?></h1><hr>
                        <?php Alert::renderFeedbackMessages(); ?>
                    </div>
                    <div class="card-body">
                        <h2 class="h3 mb-3 font-weight-normal "><?php echo $pageName ?></h3>
                        <div class="form-label-group">
                            <input type="text" name="user_name_or_email" id="inputEmail" placeholder="Gebruikersnaam of e-mailadres" class="form-control" required autofocus>
                            <label for="inputEmail">Gebruikersnaam of e-mailadres</label>
                        </div>
                        <div class="send-button">
                            <input type="submit" name="requestPasswordReset" value="Herstellen" class="btn btn-secondary mb-sm-2">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php include DIR_INCLUDES . 'footer.php'; ?>
</body>
</html>
