<?php

$pageName = 'Account activeren';
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

<?= $this->end() ?>
<?= $this->push('body') ?>

    <div class="form-group required float in" input-group="">
        <!-- <input type="text" name="user_name_or_email" id="inputEmail" placeholder="email@voorbeeld.nl" class="form-control" required autofocus> -->
        <input type="email" name="email" title="The domain portion of the email address is invalid (the portion after the @)." pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$" id="inputEmail" class="form-control" placeholder="E-mailadres" autofocus required>
        <!-- <label for="inputEmail" class="label-float">Gebruikersnaam of e-mailadres</label> -->
        <label for="inputEmail" class="label-float">E-mailadres</label>
    </div>
    <div class="form-group required float in" input-group="">
        <input type="text" minlength="32" maxlength="32" name="code" id="inputCode" class="form-control" placeholder="code" required>
        <label for="inputCode" class="label-float">Activatiecode</label>
    </div>
    <!-- <input type="submit" name="requestPasswordReset" value="Herstellen" class="btn btn-primary"> -->
    <input type="submit" name="activateSubmit" value="Activeren" class="btn btn-primary">

<?= $this->end();
