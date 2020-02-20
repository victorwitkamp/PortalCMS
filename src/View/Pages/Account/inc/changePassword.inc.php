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
            <input type="password" name="newconfirmpassword" id="newConfirmPassword" class="form-control" autocomplete="new-password" required/>
            <div class="invalid-feedback">Passwords do not match. Please repeat the password to confirm it.</div>
        </div>
    </div>

    <input type="submit" name="changepassword" value="<?= Text::get('LABEL_SUBMIT') ?>" class="btn btn-primary" />
    <script type="text/javascript">
        function validatePassword(){document.getElementById("newPassword").value===document.getElementById("newConfirmPassword").value?document.getElementById("newConfirmPassword").setCustomValidity(""):document.getElementById("newConfirmPassword").setCustomValidity("Passwords do not match. Please repeat the password to confirm it.")}window.onload=function(){document.getElementById("newPassword").onchange=validatePassword,document.getElementById("newConfirmPassword").onchange=validatePassword,Array.prototype.filter.call(document.getElementsByClassName("needs-validation"),function(e){e.addEventListener("submit",function(t){e.checkValidity()||(t.preventDefault(),t.stopPropagation()),e.classList.add("was-validated")},!1)})};
    </script>
    <script>
        $('#newPassword').PassRequirements({
            // rules: {
            //     containSpecialChars: {
            //         text: "Your input should contain at least minLength special character",
            //         minLength: 1,
            //         regex: new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g')
            //     },
            //     containNumbers: {
            //         text: "Your input should contain at least minLength numbers",
            //         minLength: 2,
            //         regex: new RegExp('[^0-9]', 'g')
            //     }
            // },
            popoverPlacement: 'bottom',
            defaults: true,
            trigger: 'click'
        });
    </script>
</form><hr>
