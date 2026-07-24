<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

$pageName = 'Wachtwoord resetten';
?>
<?= $this->layout('View::Layout/AuthenticationLayout', [ 'title' => $pageName ]) ?>
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

    <div class="mb-3 required float in">

        <input type="password" name="password" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$"
               title="Use at least 8 characters. Please include at least 1 uppercase character, 1 lowercase character and 1 number."
               id="inputPassword" class="form-control" placeholder="wachtwoord" autocomplete="new-password" required=""
               autofocus="">
        <label for="inputPassword" class="label-float">Wachtwoord</label>
    </div>
    <div class="mb-3 required float in">
        <input type="password" name="confirm_password" id="inputConfirmPassword" class="form-control"
               placeholder="Bevestig wachtwoord" data-match="wachtwoord" data-match-field="#inputPassword"
               autocomplete="new-password" required="">
        <label for="inputConfirmPassword" class="label-float">Bevestig wachtwoord</label>
    </div>
    <input type="hidden" name="username" value="<?= $this->e($username) ?>"/>
    <input type="hidden" name="password_reset_hash" value="<?= $this->e($token) ?>"/>
    <button type="submit" class="btn btn-secondary mb-sm-2">Wachtwoord wijzigen</button>

<?= $this->end();
