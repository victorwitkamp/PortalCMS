<?php

$pageName = 'Wachtwoord vergeten';
?>
<?= $this->layout('layoutLogin', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<?php //PortalCMS_JS_JQuery_Simple_validator();
?>

<?= $this->end() ?>
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

<?= $this->end() ?>
<?= $this->push('body') ?>

<div class="form-group required float in" input-group="">
    <input type="text" name="user_name_or_email" id="inputEmail" placeholder="email@voorbeeld.nl" class="form-control" required autofocus>
    <label for="inputEmail" class="label-float">Gebruikersnaam of e-mailadres</label>
</div>
<input type="submit" name="requestPasswordReset" value="Herstellen" class="btn btn-primary">

<?= $this->end();
