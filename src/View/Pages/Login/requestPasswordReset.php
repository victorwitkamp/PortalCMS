<?php

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = 'Wachtwoord vergeten';
?>
<?= $this->layout('layoutLogin', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<?php //PortalCMS_JS_JQuery_Simple_validator();
?>

<?= $this->end() ?>
<?= $this->push('body') ?>

<header>
    <div class="navbar navbar-dark bg-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/Login"><span class="fa fa-arrow-left"></span> Inloggen</a>
            </li>
        </ul>
    </div>
</header>

<div class="container-fluid container-auth">
    <div class="auth-brand m-t-md m-b-md"><?= SiteSetting::getStaticSiteSetting('site_name') ?></div>
</div>

<form method="post" validate=true>
    <div class="container-fluid container-auth">
        <div class="panel panel-auth">
            <div class="panel-heading">
                <h2 id="title-container" class="panel-title text-center"><?= Text::get('LABEL_LOG_IN') ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></h2>
                <?php Alert::renderFeedbackMessages(); ?>
            </div>
            <div class="panel-body">
                <div class="form-group required float in" input-group="">
                    <input type="text" name="user_name_or_email" id="inputEmail" placeholder="email@voorbeeld.nl" class="form-control" required autofocus>
                    <label for="inputEmail" class="label-float">Gebruikersnaam of e-mailadres</label>
                </div>
                <input type="submit" name="requestPasswordReset" value="Herstellen" class="btn btn-primary">
            </div>
            <div class="panel-footer">
                <label>Hulp bij aanmelden<div class="small"><del>Registreren</del> | <a href="/Login/RequestPasswordReset">Wachtwoord vergeten.</a></div></label>
            </div>
        </div>
    </div>
</form>

<ul class="list-inline text-center small m-t-md m-b-lg">
    <li><a href="#" class="text-muted"><del>Terms and conditions</del></a></li>
    <li><a href="#" class="text-muted"><del>Contact us</del></a></li>
</ul>
<div class="webmail-bg"></div>

<?= $this->end();
