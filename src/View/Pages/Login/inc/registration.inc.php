<div class="user_forms-signup">
    <h2 class="forms_title">Registratie</h2>
    <form method="post" class="forms_form">
        <fieldset class="forms_fieldset">
            <div class="forms_field">
            <input type="text" name="username" minlength="5" maxlength="20" placeholder="Gebruikersnaam" class="forms_field-input" autocomplete="username" required>
            </div>
            <div class="forms_field">
            <input type="email" name="email" title="The domain portion of the email address is invalid (the portion after the @)." 
            pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$" placeholder="E-mailadres" class="forms_field-input" autocomplete="email" required>
            </div>
            <div class="forms_field">
            <input type="password" name="password" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" title="Use at least 8 characters. Please include at least 1 uppercase character, 1 lowercase character and 1 number." placeholder="Wachtwoord" class="forms_field-input" autocomplete="new-password" required>
            </div>
            <div class="forms_field">
                <input type="password" name="confirm_password" placeholder="Wachtwoord bevestigen" class="forms_field-input" autocomplete="new-password" required>
            </div>
        </fieldset>
        <div class="forms_buttons">
            <input type="submit" name="signupSubmit" value="Registreren" class="btn btn-outline-info forms_buttons-action">
        </div>
    </form>
</div>
