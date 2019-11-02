<?php

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Filter\Csrf;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Text;

?>
<div class="user_forms-login">
    <h2 class="forms_title"><?php echo Text::get('LABEL_LOG_IN'); ?></h2>
    <p><?php $minutes = (Config::get('SESSION_RUNTIME') / 60); ?>
    Session duration: <?php echo $minutes ?> <?php if ($minutes > 1) {
    echo 'minutes';
} else {
    echo 'minute';
} ?><br>
    Cookie duration: <?php echo(Config::get('COOKIE_RUNTIME') / 60) . ' minutes (' . ((Config::get('COOKIE_RUNTIME') / 60) / 24) . ' hours)'; ?>
    </p>
    <form method="post">
        <div class="form-row">
            <input type="text" name="user_name" placeholder="E-mailadres of gebruikersnaam" class="form-control" autocomplete="username" required autofocus/>
        </div>
        <div class="form-row">
            <input type="password" name="user_password" placeholder="Wachtwoord" class="form-control" autocomplete="current-password" required/>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" id="rememberMe" name="set_remember_me_cookie" class="form-check-input">
            <label class="form-check-label" for="rememberMe"><?php echo Text::get('LABEL_REMEMBER_ME'); ?></label>
        </div>
        <?php
        if (!empty(Request::get('redirect'))) {
            ?><input type="hidden" name="redirect" value="<?php echo $login->View->encodeHTML(Request::get('redirect')); ?>"/><?php
        }
        // set CSRF token in login form, although sending fake login requests mightn't be interesting gap here.
        // If you want to get deeper, check these answers:
        //     1. natevw's http://stackoverflow.com/questions/6412813/do-login-forms-need-tokens-against-csrf-attacks?rq=1
        //     2. http://stackoverflow.com/questions/15602473/is-csrf-protection-necessary-on-a-sign-up-form?lq=1
        //     3. http://stackoverflow.com/questions/13667437/how-to-add-csrf-token-to-login-form?lq=1
        ?>
        <input type="hidden" name="csrf_token" value="<?php echo Csrf::makeToken(); ?>" />
        <input type="submit" name="loginSubmit" class="btn btn-success" value="<?php echo Text::get('LABEL_LOG_IN'); ?>"/>
        <a href="<?php echo $loginUrl; ?>" class="btn btn-info"><i class="fab fa-facebook"></i><?php echo Text::get('LABEL_CONTINUE_WITH_FACEBOOK'); ?></a>
        <hr>
        <a href="requestPasswordReset.php"><?php echo Text::get('LABEL_FORGOT_PASSWORD'); ?></a>
    </form>
</div>
