<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
if ($pageType === 'edit') {
    $edit = true;
} else {
    $edit = false;
}
?>
<form method="post" <?php
if ($edit) {
    echo 'validate=true';
} ?>>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Jaar van lidmaatschap</label>
                    <input type="number" minlength="4" maxlength="4" name="jaarlidmaatschap" class="form-control"
                           value="<?php if ($edit) { echo $member->jaarlidmaatschap; } ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">Voorletters</label>
                    <input type="text" name="voorletters" value="<?php
                    if ($edit) {
                        echo $member->voorletters;
                    } ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Voornaam</label>
                    <input type="text" name="voornaam" value="<?php
                    if ($edit) {
                        echo $member->voornaam;
                    } ?>" class="form-control" autocomplete="given-name">
                </div>
                <div class="col-md-5">
                    <label class="col-form-label">Achternaam</label>
                    <input type="text" name="achternaam" value="<?php
                    if ($edit) {
                        echo $member->achternaam;
                    } ?>" class="form-control" autocomplete="family-name">
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
                               name="geboortedatum" value="<?php
                        if ($edit) {
                            if (!empty($member->geboortedatum)) {
                                echo date('Y-m-d', strtotime($member->geboortedatum));
                            }
                        } ?>" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-9">
                    <label class="col-form-label">Adres</label>
                    <input type="text" name="adres" value="<?php
                    if ($edit) {
                        echo $member->adres;
                    } ?>" class="form-control" placeholder="Voorbeeldadres 123" autocomplete="street-address">
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Huisnummer</label>
                    <input type="text" name="huisnummer" value="<?php
                    if ($edit) {
                        echo $member->huisnummer;
                    } ?>" class="form-control" maxlength="6" placeholder="123">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-5">
                    <label class="col-form-label">Postcode</label>
                    <input type="text" name="postcode" value="<?php
                    if ($edit) {
                        echo $member->postcode;
                    } ?>" class="form-control" minlength="6" maxlength="6" placeholder="1234AB"
                           autocomplete="postal-code">
                </div>
                <div class="col-md-7">
                    <label class="col-form-label">Woonplaats</label>
                    <input type="text" name="woonplaats" value="<?php
                    if ($edit) {
                        echo $member->woonplaats;
                    } ?>" class="form-control" placeholder="Barendrecht" autocomplete="address-level2">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-form-label">Telefoon vast</label>
                    <input type="tel" name="telefoon_vast" value="<?php
                    if ($edit) {
                        echo $member->telefoon_vast;
                    } ?>" class="form-control" autocomplete="tel">
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">Telefoon mobiel</label>
                    <input type="tel" name="telefoon_mobiel" value="<?php
                    if ($edit) {
                        echo $member->telefoon_mobiel;
                    } ?>" class="form-control" autocomplete="tel">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">Emailadres</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">@</div>
                        </div>
                        <input type="email" name="emailadres" value="<?php
                        if ($edit) {
                            echo $member->emailadres;
                        } ?>" class="form-control" autocomplete="email">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="col-form-label">Lid vanaf</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                        <input type="date"
                               pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                               name="ingangsdatum" value="<?php
                        if ($edit) {
                            if (!empty($member->ingangsdatum)) {
                                echo date('Y-m-d', strtotime($member->ingangsdatum));
                            }
                        } ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="col-form-label">Geslacht</label>
                <select name="geslacht" class="form-control">
                    <option></option>
                    <option value="Man" <?php
                    if ($edit && $member->geslacht === 'Man') {
                        echo 'selected';
                    } ?>>Man
                    </option>
                    <option value="Vrouw" <?php
                    if ($edit && $member->geslacht === 'Vrouw') {
                        echo 'selected';
                    } ?>>Vrouw
                    </option>
                    <option value="nvt" <?php
                    if ($edit && $member->geslacht === 'nvt') {
                        echo 'selected';
                    } ?>>N.v.t.
                    </option>
                </select>
                <label class="col-form-label">Nieuwsbrief ontvangen?</label>
                <select name="nieuwsbrief" class="form-control">
                    <option></option>
                    <option value="1" <?php
                    if ($edit && $member->nieuwsbrief === 1) {
                        echo 'selected';
                    } ?>>Ja
                    </option>
                    <option value="0" <?php
                    if ($edit && $member->nieuwsbrief === 0) {
                        echo 'selected';
                    } ?>>Nee
                    </option>
                </select>
            </div>

            <div class="card">
                <div class="card-header">
                    <p class="card-title">Zou je vrijwilliger willen zijn?</p>
                </div>
                <div class="card-body">
                    <select name="vrijwilliger" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->vrijwilliger === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilliger === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
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
                        <option value="1" <?php
                        if ($edit && $member->vrijwilligeroptie1 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilligeroptie1 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Bar</label>
                    <select name="vrijwilligeroptie2" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->vrijwilligeroptie2 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilligeroptie2 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Schoonmaken / klussen</label>
                    <select name="vrijwilligeroptie3" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->vrijwilligeroptie3 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilligeroptie3 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Promotie / flyeren</label>
                    <select name="vrijwilligeroptie4" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->vrijwilligeroptie4 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilligeroptie4 === 0) {
                            echo 'selected';
                        } ?>>Nee
                        </option>
                    </select>
                    <label class="col-form-label">Organisatie evenementen</label>
                    <select name="vrijwilligeroptie5" class="form-control">
                        <option></option>
                        <option value="1" <?php
                        if ($edit && $member->vrijwilligeroptie5 === 1) {
                            echo 'selected';
                        } ?>>Ja
                        </option>
                        <option value="0" <?php
                        if ($edit && $member->vrijwilligeroptie5 === 0) {
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
                <option value="contant" <?php
                if ($edit && $member->betalingswijze === 'contant') {
                    echo 'selected';
                } ?>>Contant
                </option>
                <option value="pin" <?php
                if ($edit && $member->betalingswijze === 'pin') {
                    echo 'selected';
                } ?>>Pin
                </option>
                <option value="incasso" <?php
                if ($edit && $member->betalingswijze === 'incasso') {
                    echo 'selected';
                } ?>>Automatische incasso
                </option>
                <option value="gratisbestuur" <?php
                if ($edit && $member->betalingswijze === 'gratisbestuur') {
                    echo 'selected';
                } ?>>Gratis (bestuur)
                </option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Rekeningnummer (IBAN)</label>
            <input type="text" name="iban" value="<?php
            if ($edit) {
                echo $member->iban;
            } ?>" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Machtigingskenmerk</label>
            <input type="text" name="iban" value="<?php
            if ($edit) {
                echo $member->machtigingskenmerk;
            } ?>" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="col-form-label">Status</label>
            <select name="status" class="form-control">
                <option value="0" <?php
                if ($edit && $member->status === 0) {
                    echo 'selected';
                } ?>>0. Nieuw
                </option>
                <option value="1" <?php
                if ($edit && $member->status === 1) {
                    echo 'selected';
                } ?>>1. Incasso opdracht verzonden
                </option>
                <option value="1" <?php
                if ($edit && $member->status === 11) {
                    echo 'selected';
                } ?>>1.1 Niet verstuurd: rekeningnummer onjuist
                </option>
                <option value="2" <?php
                if ($edit && $member->status === 2) {
                    echo 'selected';
                } ?>>2. Betaling per incasso gelukt
                </option>
                <option value="2" <?php
                if ($edit && $member->status === 21) {
                    echo 'selected';
                } ?>>2.1 Incasso mislukt: rekeningnummer onjuist
                </option>
                <option value="3" <?php
                if ($edit && $member->status === 3) {
                    echo 'selected';
                } ?>>3
                </option>
                <option value="4" <?php
                if ($edit && $member->status === 4) {
                    echo 'selected';
                } ?>>4
                </option>
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
                    <input type="hidden" name="id" value="<?php
                    if ($edit) {
                        echo $member->id;
                    } ?>">
                    <input type="submit" <?php
                    if ($edit) {
                        echo 'name="saveMember"';
                    } else {
                        echo 'name="saveNewMember"';
                    } ?> class="btn btn-sm btn-primary" value="Opslaan">
                    <a href="/Membership" class="btn btn-sm btn-danger">Annuleren</a>
                </div>
            </div>
        </div>
    </div>
</form>
