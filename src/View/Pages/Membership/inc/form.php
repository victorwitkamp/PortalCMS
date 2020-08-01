<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
$edit = ($pageType === 'edit');
?>
<form method="post"<?= ($edit) ? ' validate=true' : '' ?>>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Jaar van lidmaatschap</label>
                    <input type="number" minlength="4" maxlength="4" name="jaarlidmaatschap" class="form-control"
                           value="<?= ($edit) ? $member->jaarlidmaatschap : '' ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">Voorletters</label>
                    <input type="text" name="voorletters" value="<?= ($edit) ? $member->voorletters : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Voornaam</label>
                    <input type="text" name="voornaam" value="<?= ($edit) ? $member->voornaam : '' ?>" class="form-control" autocomplete="given-name">
                </div>
                <div class="col-md-5">
                    <label class="col-form-label">Achternaam</label>
                    <input type="text" name="achternaam" value="<?= ($edit) ? $member->achternaam : '' ?>" class="form-control" autocomplete="family-name">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">Geboortedatum</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                        <input type="date"
                               pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                               name="geboortedatum" value="<?= ($edit && !empty($member->geboortedatum)) ? date('Y-m-d', strtotime($member->geboortedatum)) : '' ?>" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-9">
                    <label class="col-form-label">Adres</label>
                    <input type="text" name="adres" value="<?= ($edit) ? $member->address->adres : '' ?>" class="form-control" placeholder="Voorbeeldadres 123" autocomplete="street-address">
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Huisnummer</label>
                    <input type="text" name="huisnummer" value="<?= ($edit) ? $member->address->huisnummer : '' ?>" class="form-control" maxlength="6" placeholder="123">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-5">
                    <label class="col-form-label">Postcode</label>
                    <input type="text" name="postcode" value="<?= ($edit) ? $member->address->postcode : '' ?>" class="form-control" minlength="6" maxlength="6" placeholder="1234AB"
                           autocomplete="postal-code">
                </div>
                <div class="col-md-7">
                    <label class="col-form-label">Woonplaats</label>
                    <input type="text" name="woonplaats" value="<?= ($edit) ? $member->address->woonplaats : '' ?>" class="form-control" placeholder="Barendrecht" autocomplete="address-level2">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">Telefoon vast</label>
                    <input type="tel" name="telefoon_vast" value="<?= ($edit) ? $member->contactDetails->telefoon_vast : '' ?>" class="form-control" autocomplete="tel">
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">Telefoon mobiel</label>
                    <input type="tel" name="telefoon_mobiel" value="<?= ($edit) ? $member->contactDetails->telefoon_mobiel : '' ?>" class="form-control" autocomplete="tel">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">Emailadres</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text">@</div></div>
                        <input type="email" name="emailadres" value="<?= ($edit) ? $member->contactDetails->emailadres : '' ?>" class="form-control" autocomplete="email">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">Lid vanaf</label>
                    <div class="input-group">
                        <div class="input-group-append"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div>
                        <input type="date"
                               pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                               name="ingangsdatum" value="<?= ($edit && !empty($member->ingangsdatum)) ? date('Y-m-d', strtotime($member->ingangsdatum)) : '' ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="col-form-label">Geslacht</label>
                <select name="geslacht" class="form-control">
                    <option></option>
                    <option value="Man" <?= ($edit && $member->geslacht === 'Man') ? 'selected' : '' ?>>Man</option>
                    <option value="Vrouw" <?= ($edit && $member->geslacht === 'Vrouw') ? 'selected' : '' ?>>Vrouw</option>
                    <option value="nvt" <?= ($edit && $member->geslacht === 'nvt') ? 'selected' : '' ?>>N.v.t.</option>
                </select>
                <label class="col-form-label">Nieuwsbrief ontvangen?</label>
                <select name="nieuwsbrief" class="form-control">
                    <option></option>
                    <option value="1" <?= ($edit && $member->preferences->nieuwsbrief === 1) ? 'selected' : '' ?>>Ja</option>
                    <option value="0" <?= ($edit && $member->preferences->nieuwsbrief === 0) ? 'selected' : '' ?>>Nee</option>
                </select>
            </div>

            <div class="card">
                <div class="card-header">
                    <p class="card-title">Zou je vrijwilliger willen zijn?</p>
                </div>
                <div class="card-body">
                    <select name="vrijwilliger" class="form-control">
                        <option></option>
                        <option value="1" <?= ($edit && $member->preferences->vrijwilliger === 1) ? 'selected' : '' ?>>Ja</option>
                        <option value="0" <?= ($edit && $member->preferences->vrijwilliger === 0) ? 'selected' : '' ?>>Nee</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <p class="card-title">Waar wil je ingezet worden?</p>
                </div>
                <div class="card-body">
                    <label class="col-form-label">Licht / geluid</label>
                    <select name="vrijwilligeroptie1" class="form-control">
                        <option></option>
                        <option value="1" <?= ($edit && $member->preferences->vrijwilligeroptie1 === 1) ? 'selected' : '' ?>>Ja</option>
                        <option value="0" <?= ($edit && $member->preferences->vrijwilligeroptie1 === 0) ? 'selected' : '' ?>>Nee</option>
                    </select>
                    <label class="col-form-label">Bar</label>
                    <select name="vrijwilligeroptie2" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie2 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie2 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Schoonmaken / klussen</label>
                    <select name="vrijwilligeroptie3" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie3 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie3 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Promotie / flyeren</label>
                    <select name="vrijwilligeroptie4" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie4 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie4 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Organisatie evenementen</label>
                    <select name="vrijwilligeroptie5" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie5 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->preferences->vrijwilligeroptie5 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Betalingswijze</label>
            <select name="betalingswijze" class="form-control">
                <option></option>
                <option value="contant" <?= ($edit && $member->paymentDetails->betalingswijze === 'contant') ? 'selected' : '' ?>>Contant</option>
                <option value="pin" <?= ($edit && $member->paymentDetails->betalingswijze === 'pin') ? 'selected' : '' ?>>Pin</option>
                <option value="incasso" <?= ($edit && $member->paymentDetails->betalingswijze === 'incasso') ? 'selected' : '' ?>>Automatische incasso</option>
                <option value="gratisbestuur" <?= ($edit && $member->paymentDetails->betalingswijze === 'gratisbestuur') ? 'selected' : '' ?>>Gratis (bestuur)</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Rekeningnummer (IBAN)</label>
            <input type="text" name="iban" value="<?= ($edit) ? $member->paymentDetails->iban : '' ?>" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Machtigingskenmerk</label>
            <input type="text" name="iban" value="<?= ($edit) ? $member->paymentDetails->machtigingskenmerk : '' ?>" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Status</label>
            <select name="status" class="form-control">
                <option value="0" <?= ($edit && $member->paymentDetails->status === 0) ? 'selected' : '' ?>>0. Nieuw</option>
                <option value="1" <?= ($edit && $member->paymentDetails->status === 1) ? 'selected' : '' ?>>1. Incasso opdracht verzonden</option>
                <option value="1" <?= ($edit && $member->paymentDetails->status === 11) ? 'selected' : '' ?>>1.1 Niet verstuurd: rekeningnummer onjuist</option>
                <option value="2" <?= ($edit && $member->paymentDetails->status === 2) ? 'selected' : '' ?>>2. Betaling per incasso gelukt</option>
                <option value="2" <?= ($edit && $member->paymentDetails->status === 21) ? 'selected' : '' ?>>2.1 Incasso mislukt: rekeningnummer onjuist</option>
                <option value="3" <?= ($edit && $member->paymentDetails->status === 3) ? 'selected' : '' ?>>3</option>
                <option value="4" <?= ($edit && $member->paymentDetails->status === 4) ? 'selected' : '' ?>>4</option>
            </select>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <p>* = verplicht veld.</p>
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">&#xA0;</label>
                    <input type="hidden" name="id" value="<?= ($edit) ? $member->id : '' ?>">
                    <input type="submit" <?= ($edit) ? 'name="saveMember"' : 'name="saveNewMember"' ?> class="btn btn-sm btn-primary" value="Opslaan">
                    <a href="/Membership" class="btn btn-sm btn-danger">Annuleren</a>
                </div>
            </div>
        </div>
    </div>
</form>
