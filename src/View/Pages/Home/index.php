<?php
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_HOME');
$theme = SiteSetting::getStaticSiteSetting('site_theme');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?= SiteSetting::getStaticSiteSetting('site_name') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/<?= $theme ?>/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <script src="/includes/js/avantui.js"></script>
    <script src="/dist/cookieconsent/build/cookieconsent.min.js" async></script>
    <script src="/includes/js/cookieconsent.init.js" async></script>
</head>

<body>
<?php require DIR_INCLUDES . 'nav.php'; ?>
<main>
    <div class="content">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <img src='<?= SiteSetting::getStaticSiteSetting('site_logo') ?>' alt='logo' width='120px' height='120px' />
                        <?php if (Authorization::hasPermission('site-settings')) { ?>
                            <br><a href="/settings/logo/">Logo wijzigen</a>
                        <?php } ?>

                    </div>
                    <div class="col-sm-9">
                        <h1><?= SiteSetting::getStaticSiteSetting('site_name') ?></h1>
                        <p class="lead">
                        <?php
                        if (SiteSetting::getStaticSiteSetting('site_description_type') === '1') {
                            echo SiteSetting::getStaticSiteSetting('site_description');
                        }
                        require 'slogan.php';
                        ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php
                $layout = SiteSetting::getStaticSiteSetting('site_layout');
                require 'layouts/' . $layout . '.php';
                ?>
            </div>
        </div>
    </div>
</main>
<?php include DIR_INCLUDES . 'footer.php'; ?>
</body>

</html>
