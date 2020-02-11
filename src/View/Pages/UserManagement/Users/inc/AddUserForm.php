<?php
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;
?>
<form method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h3>Algemeen</h3>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Gebruikersnaam</label>
                        <input type="text" name="user_name" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">E-mailaddress</label>
                        <input type="text" name="user_email" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Wachtwoord</label>
                        <input type="password" name="user_password" class="form-control" required>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <input type="submit" name="addNewUser" class="btn btn-primary" value="<?= Text::get('LABEL_SUBMIT') ?>">
        <a href="/UserManagement/Users" class="btn btn-danger"><?= Text::get('LABEL_CANCEL') ?></a>
    </div>
</form>