
<?php use PortalCMS\Core\Config;
use PortalCMS\Core\Csrf;
use PortalCMS\Core\Request;
use PortalCMS\Core\Text;

?>
<div class="user_forms-login">
    <h2 class="forms_title"><?php

        echo Text::get('LABEL_LOG_IN'); ?></h2>
    <p><?php
    $minutes = (Config::get('SESSION_RUNTIME') / 60);
    if ($minutes > 1) { $minutestext = 'minutes';
    } else { $minutestext = 'minute';
    }
    ?>
    Session duration: <?php echo $minutes.' '.$minutestext; ?><br>
    Cookie duration: <?php echo (Config::get('COOKIE_RUNTIME') / 60).' minutes ('.((Config::get('COOKIE_RUNTIME') / 60) / 24).' hours)'; ?>
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
        // when a user navigates to a page that's only accessible for logged a logged-in user, then
        // the user is sent to this page here, also having the page he/she came from in the URL parameter
        // (have a look). This "where did you came from" value is put into this form to sent the user back
        // there after being logged in successfully.
        // Simple but powerful feature, big thanks to @tysonlist.
        if (!empty(Request::get('redirect'))) {
            echo '<input type="hidden" name="redirect" value="'.$login->View->encodeHTML(Request::get('redirect')).'" />';
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
