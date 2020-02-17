<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\View\Text;

?>
<h3><?= Text::get('LABEL_CHANGE_PASSWORD') ?></h3>
<form method="post" id="changePasswordForm" class="needs-validation" novalidate>
    <input type="text" class="d-none" value="<?= Session::get('user_name') ?>" autocomplete="username" required />
    <div class="form-group row">
        <label for="currentPassword" class="col-sm-4 col-form-label"><?= Text::get('LABEL_CURRENT_PASSWORD') ?></label>
        <div class="col-sm-8">
            <input type="password" name="currentpassword" id="currentPassword" class="form-control" autocomplete="current-password" required />
        </div>
    </div>
    <div class="form-group row">
        <label for="newPassword" class="col-sm-4 col-form-label"><?= Text::get('LABEL_NEW_PASSWORD') ?></label>
        <div class="col-sm-8">
            <input type="password" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Use at least 8 characters. Please include at least 1 uppercase character, 1 lowercase character and 1 number." name="newpassword" id="newPassword" class="form-control" autocomplete="new-password" data-validate-length="6,8" required />
            <small id="emailHelp" class="form-text text-muted">(Minimaal 8 tekens. Minimaal 1 hoofdletter, 1 kleine letter en 1 cijfer)</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="newConfirmPassword" class="col-sm-4 col-form-label"><?= Text::get('LABEL_CONFIRM_PASSWORD') ?></label>
        <div class="col-sm-8">
            <input type="password" name="newconfirmpassword" id="newConfirmPassword" class="form-control" autocomplete="new-password" required />
        </div>
    </div>
    <script>
        var password = document.getElementById("newPassword"),
            confirm_password = document.getElementById("newConfirmPassword");
        function validatePassword(){
            if (password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Passwords do not match");
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        validatePassword();
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <input type="submit" name="changepassword" value="<?= Text::get('LABEL_SUBMIT') ?>" class="btn btn-primary" />
</form>
