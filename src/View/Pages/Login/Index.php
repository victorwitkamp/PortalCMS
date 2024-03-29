<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Csrf;
use PortalCMS\Core\View\HTMLEntities;
use PortalCMS\Core\View\Text;

//require DIR_ROOT . 'login/ext/fb/config.php';
//$loginUrl = $helper->getLoginUrl(Config::get('FB_LOGIN_URL'), [ 'email' ]);
$pageName = Text::get('LABEL_LOG_IN');
?>

<?= $this->layout('layoutLogin', [ 'title' => $pageName ]) ?>
<?= $this->push('body-start') ?>
<?= $this->end() ?>
<?= $this->push('body') ?>
    <div class="form-group required float in">
        <input type="text" class="form-control" id="email" name="user_name" placeholder="Gebruikersnaam" autocomplete="username" required autofocus/>
        <label for="text" class="label-float">Gebruikersnaam</label>
    </div>
    <div class="form-group required float in">
        <input type="password" class="form-control" id="password" name="user_password" placeholder="Wachtwoord" autocomplete="current-password" required/>
        <label for="password" class="label-float">Wachtwoord</label>
    </div>
    <div class="form-group form-check">
        <label class="form-check-label" for="rememberMe">
            <input type="checkbox" id="rememberMe" name="set_remember_me_cookie" class="form-check-input"/> <?= Text::get('LABEL_REMEMBER_ME') ?>
        </label>
    </div>
    <hr/>
    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken() ?>"/>
    <?php
    if (!empty(Request::get('redirect'))) {
        ?><input type="hidden" name="redirect" value="<?= HTMLEntities::encode((string)Request::get('redirect')) ?>" /><?php
    }
    ?>
    <input type="submit" name="loginSubmit" class="btn btn-primary" value="<?= Text::get('LABEL_LOG_IN') ?>"/>
<?= $this->end();
