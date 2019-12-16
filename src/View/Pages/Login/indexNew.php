<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;
use PortalCMS\Core\View\View;

require $_SERVER['DOCUMENT_ROOT'] . '/Login/ext/fb/config.php';
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(Config::get('FB_LOGIN_URL'), $permissions);
$pageName = 'Login';
?>

<?= $this->layout('layoutLogin', ['title' => $pageName]) ?>
<?= $this->push('body') ?>

<?php //require 'inc/loadingAnimation.php'; ?>

<div class="container-fluid container-auth">
    <div class="auth-brand m-t-md m-b-md"><?= SiteSetting::getStaticSiteSetting('site_name') ?></div>
</div>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken() ?>" />
    <?php if (!empty(Request::get('redirect'))) { ?><input type="hidden" name="redirect" value="<?= View::encodeHTML(Request::get('redirect')) ?>" /><?php } ?>
    <div class="container-fluid container-auth">
        <div class="panel panel-auth">
            <div class="panel-heading">
                <h2 id="title-container" class="panel-title text-center"><?= Text::get('LABEL_LOG_IN') ?> - <?= SiteSetting::getStaticSiteSetting('site_name') ?></h2>
                <?php Alert::renderFeedbackMessages(); ?>
            </div>
            <div class="panel-body">
                <div class="form-group required float in" input-group="">
                    <input type="text" class="form-control" id="email" name="user_name" placeholder="Gebruikersnaam" autocomplete="username" required autofocus/>
                    <label for="text" class="label-float">Gebruikersnaam</label>
                </div>
                <div class="form-group required float in" input-group="">
                    <input type="password" class="form-control" id="password" name="user_password" placeholder="Wachtwoord" autocomplete="current-password" required/>
                    <label for="password" class="label-float">Wachtwoord</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" id="rememberMe" name="set_remember_me_cookie" class="form-check-input">
                    <label class="form-check-label" for="rememberMe"><?= Text::get('LABEL_REMEMBER_ME') ?></label>
                </div>
                <hr />
                <input type="submit" name="loginSubmit" class="btn btn-primary" value="<?= Text::get('LABEL_LOG_IN') ?>" />
                <a href="<?= $loginUrl ?>" class="btn btn-info"><i class="fab fa-facebook"></i> <?= Text::get('LABEL_CONTINUE_WITH_FACEBOOK') ?></a>
            </div>
            <div class="panel-footer">
                <label>Hulp bij aanmelden<div class="small"><del>Registreren</del> | <a href="/Login/RequestPasswordReset">Wachtwoord vergeten.</a></div></label>
            </div>
        </div>
        <ul class="list-inline text-center small m-t-md">
            <li><i class="fas fa-globe text-muted"></i></li>
            <li><a class="text-muted" href="#"><del>English</del></a></li>
            <li><a class="text-muted" href="#"><del>Nederlands</del></a></li>
            <!-- <li><a class="text-muted" href="">Español</a></li> -->
        </ul>
    </div>
</form>
<ul class="list-inline text-center small m-t-md m-b-lg">
    <li><a href="#" class="text-muted"><del>Terms and conditions</del></a></li>
    <li><a href="#" class="text-muted"><del>Contact us</del></a></li>
</ul>
<div class="webmail-bg"></div>

<?= $this->end();
