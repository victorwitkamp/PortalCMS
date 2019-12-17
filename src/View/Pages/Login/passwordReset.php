<?php

use League\Plates\Engine;
use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Alert;

$pageName = 'Wachtwoord resetten';

if (empty($_GET['password_reset_hash'])) {
    header('HTTP/1.1 401 Unauthorized ', true, 401);
    $templates = new Engine(DIR_VIEW);
    echo $templates->render('Pages/Error/Error', ['title' => '401 - Unauthorized', 'message' => 'Invalid token. - Unauthorized']);
    die;
}
?>
<?= $this->layout('layoutLogin', ['title' => $pageName]) ?>
<?= $this->push('body-start') ?>

<header>
    <div class="navbar navbar-dark bg-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/Login"><span class="fa fa-arrow-left"></span> Inloggen</a>
            </li>
        </ul>
    </div>
</header>

<?= $this->end(); ?>
<?= $this->push('body') ?>

    <div class="form-group required float in" input-group="">

        <input type="password" name="password" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Use at least 8 characters. Please include at least 1 uppercase character, 1 lowercase character and 1 number." id="inputPassword" class="form-control" placeholder="wachtwoord" autocomplete="new-password" required="" autofocus="" <?php
                                                                                                                                                                                                                                                                                                                                                                if (empty($_GET['password_reset_hash'])) {
                                                                                                                                                                                                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                                                                                                                                                                                                } ?>>
        <label for="inputPassword" class="label-float">Wachtwoord</label>
    </div>
    <div class="form-group required float in" input-group="">
        <input type="password" name="confirm_password" id="inputConfirmPassword" class="form-control" placeholder="Bevestig wachtwoord" data-match="wachtwoord" data-match-field="#inputPassword" autocomplete="new-password" required="" <?php
                                                                                                                                                                                                                                            if (empty($_GET['password_reset_hash'])) {
                                                                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                                                                            } ?>>
        <label for="inputConfirmPassword" class="label-float">Bevestig wachtwoord</label>
    </div>
    <input type="hidden" name="username" value="<?= $_GET['username'] ?>" />
    <input type="hidden" name="password_reset_hash" value="<?= $_GET['password_reset_hash'] ?>" />
    <input type="submit" name="resetSubmit" value="Wachtwoord wijzigen" class="btn btn-secondary mb-sm-2" <?php
                                                                                                            if (empty($_GET['password_reset_hash'])) {
                                                                                                                echo 'disabled';
                                                                                                            } ?>>

<?= $this->end();
