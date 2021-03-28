<?php
declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<form method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h3><?= Text::get('LABEL_ACCOUNT_DETAILS') ?></h3>
                <div class="row">
                    <div class="col-md-12">
                        <label for="UserName" class="control-label"><?= Text::get('LABEL_USER_NAME') ?></label>
                        <input type="text" id="UserName" name="user_name" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="UserEmail" class="control-label"><?= Text::get('LABEL_USER_EMAIL') ?></label>
                        <input type="text" id="UserEmail" name="user_email" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="UserPassword" class="control-label"><?= Text::get('LABEL_USER_PASSWORD') ?></label>
                        <input type="password" id="UserPassword" name="user_password" class="form-control" required>
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