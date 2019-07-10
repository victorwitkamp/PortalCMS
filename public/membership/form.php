<form method="post" <?php if ($pageType === 'edit') { echo 'validate=true'; } ?>>

<div class="row">
    <div class="col-md-8">

        <div class="form-group row">
            <div class="col-md-4">
                <label class="col-form-label">Jaar van lidmaatschap</label>
                <input type="text" name="jaarlidmaatschap" class="form-control" placeholder="" value="<?php if ($pageType === 'edit') { echo $row ['jaarlidmaatschap']; } ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label class="col-form-label">Voorletters</label>
                <input type="text" name="voorletters" value="<?php if ($pageType === 'edit') { echo $row ['voorletters']; } ?>" class="form-control" placeholder="" required>
            </div>
            <div class="col-md-4">
                <label class="col-form-label">Voornaam</label>
                <input type="text" name="voornaam" value="<?php if ($pageType === 'edit') { echo $row ['voornaam']; } ?>" class="form-control" placeholder="" autocomplete="given-name" required>
            </div>
            <div class="col-md-5">
                <label class="col-form-label">Achternaam</label>
                <input type="text" name="achternaam" value="<?php if ($pageType === 'edit') { echo $row ['achternaam']; } ?>" class="form-control" placeholder="" autocomplete="family-name" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label class="col-form-label">Geboortedatum</label>
                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    <input type="text" name="geboortedatum" value="<?php if ($pageType === 'edit') { echo $row ['geboortedatum']; } ?>" class="form-control  datetimepicker-input" data-target="#datetimepicker1" required>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-9">
                <label class="col-form-label">Adres</label>
                <input type="text" name="adres" value="<?php if ($pageType === 'edit') { echo $row ['adres']; } ?>" class="form-control" placeholder="Voorbeeldadres 123" autocomplete="street-address" required>
            </div>
            <div class="col-md-3">
                <label class="col-form-label">Huisnummer</label>
                <input type="text" name="huisnummer" value="<?php if ($pageType === 'edit') { echo $row ['huisnummer']; } ?>"  class="form-control" maxlength="6" placeholder="123" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-5">
                <label class="col-form-label">Postcode</label>
                <input type="text" name="postcode" value="<?php if ($pageType === 'edit') { echo $row ['postcode']; } ?>"  class="form-control" maxlength="6" placeholder="1234AB" autocomplete="postal-code" required>
            </div>
            <div class="col-md-7">
                <label class="col-form-label">Woonplaats</label>
                <input type="text" name="woonplaats" value="<?php if ($pageType === 'edit') { echo $row ['woonplaats']; } ?>" class="form-control" placeholder="Barendrecht" autocomplete="address-level2" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label class="col-form-label">Telefoon vast</label>
                <input type="text" name="telefoon_vast" value="<?php if ($pageType === 'edit') { echo $row ['telefoon_vast']; } ?>" class="form-control" placeholder="" autocomplete="tel" required>
            </div>
            <div class="col-md-6">
                <label class="col-form-label">Telefoon mobiel</label>
                <input type="text" name="telefoon_mobiel" value="<?php if ($pageType === 'edit') { echo  $row ['telefoon_mobiel']; } ?>" class="form-control" placeholder="" autocomplete="tel" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label class="col-form-label">Emailadres</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">@</div>
                    </div>
                    <input type="text" name="emailadres" value="<?php if ($pageType === 'edit') { echo $row ['emailadres']; } ?>" class="form-control" placeholder="" autocomplete="email" required>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label class="col-form-label">Lid vanaf</label>
                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    <input type="text" name="ingangsdatum" value="<?php if ($pageType === 'edit') { echo $row ['ingangsdatum']; } ?>" class="form-control datetimepicker-input" data-target="#datetimepicker2" required>
                </div>
            </div>
        </div>

    </div>


    <div class="col-md-4">

        <div class="form-group">
            <label class="col-form-label">Geslacht</label>
            <select name="geslacht" class="form-control" required>
                <option></option>
                <option value="Man" <?php if ($pageType === 'edit') { if ($row ['geslacht'] === 'Man') { echo 'selected'; } } ?>>Man</option>
                <option value="Vrouw" <?php if ($pageType === 'edit') { if ($row ['geslacht'] === 'Vrouw') { echo 'selected'; } }?>>Vrouw</option>
                <option value="nvt" <?php if ($pageType === 'edit') { if ($row ['geslacht'] === 'nvt') { echo 'selected'; } }?>>N.v.t.</option>
            </select>

            <label class="col-form-label">Nieuwsbrief ontvangen?</label>
            <select name="nieuwsbrief" class="form-control" required>
                <option></option>
                <option value="1" <?php if ($pageType === 'edit') { if ($row ['nieuwsbrief'] === '1') { echo 'selected'; } }?>>Ja</option>
                <option value="0" <?php if ($pageType === 'edit') { if ($row ['nieuwsbrief'] === '0') { echo 'selected'; } }?>>Nee</option>
            </select>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title">Zou je vrijwilliger willen zijn?</p>
            </div>
            <div class="card-body">
                <select name="vrijwilliger" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilliger'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilliger'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title">Waar wil je ingezet worden?</p>
            </div>
            <div class="card-body">
                <label class="col-form-label">Licht / geluid</label>
                <select name="vrijwilligeroptie1" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie1'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie1'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
                <label class="col-form-label">Bar</label>
                <select name="vrijwilligeroptie2" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie2'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie2'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
                <label class="col-form-label">Schoonmaken / klussen</label>
                <select name="vrijwilligeroptie3" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie3'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie3'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
                <label class="col-form-label">Promotie / flyeren</label>
                <select name="vrijwilligeroptie4" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie4'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie4'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
                <label class="col-form-label">Organisatie evenementen</label>
                <select name="vrijwilligeroptie5" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie5'] === '1') { echo 'selected'; } } ?>>Ja</option>
                    <option value="0" <?php if ($pageType === 'edit') { if ($row ['vrijwilligeroptie5'] === '0') { echo 'selected'; } } ?>>Nee</option>
                </select>
            </div>
        </div>

    </div>

</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <label class="col-form-label">Betalingswijze</label>
        <select name="betalingswijze" class="form-control" required>
            <option></option>
            <option value="contant" <?php if ($pageType === 'edit') { if ($row ['betalingswijze'] === 'contant') { echo 'selected'; } } ?>>Contant</option>
            <option value="pin" <?php if ($pageType === 'edit') { if ($row ['betalingswijze'] === 'pin') { echo 'selected'; } } ?>>Pin</option>
            <option value="incasso" <?php if ($pageType === 'edit') { if ($row ['betalingswijze'] === 'incasso') { echo 'selected'; } } ?>>Automatische incasso</option>
            <option value="gratisbestuur" <?php if ($pageType === 'edit') { if ($row ['betalingswijze'] === 'gratisbestuur') { echo 'selected'; } } ?>>Gratis (bestuur)</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="col-form-label">Rekeningnummer (IBAN)</label>
        <input type="text" name="iban" value="<?php if ($pageType === 'edit') { echo $row ['iban']; } ?>" class="form-control">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="col-form-label">Machtigingskenmerk</label>
        <input type="text" name="iban" value="<?php if ($pageType === 'edit') { echo $row ['machtigingskenmerk']; } ?>" class="form-control">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="col-form-label">Status</label>
        <select name="status" class="form-control">
            <option value="0" <?php if ($pageType === 'edit') { if ($row ['status'] === '0') { echo 'selected'; } } ?>>0. Nieuw</option>
            <option value="1" <?php if ($pageType === 'edit') { if ($row ['status'] === '1') { echo 'selected'; } } ?>>1. Incasso opdracht verzonden</option>
            <option value="1" <?php if ($pageType === 'edit') { if ($row ['status'] === '11') { echo 'selected'; } } ?>>1.1 Niet verstuurd: rekeningnummer onjuist</option>
            <option value="2" <?php if ($pageType === 'edit') { if ($row ['status'] === '2') { echo 'selected'; } } ?>>2. Betaling per incasso gelukt</option>
            <option value="2" <?php if ($pageType === 'edit') { if ($row ['status'] === '21') { echo 'selected'; } } ?>>2.1 Incasso mislukt: rekeningnummer onjuist</option>
            <option value="3" <?php if ($pageType === 'edit') { if ($row ['status'] === '3') { echo 'selected'; } } ?>>3</option>
            <option value="4" <?php if ($pageType === 'edit') { if ($row ['status'] === '4') { echo 'selected'; } } ?>>4</option>
        </select>
    </div>
</div>

<hr>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Machtiging automatische incasso</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <img src="/content/img/beuklogo_transparant.png" alt="De Beuk" height="120" width="120">
                    </div>
                    <div class="col-md-3">
                        <strong>Naam incassant<br> Adres<br> Postcode<br> Woonplaats<br>
                        </strong>
                    </div>
                    <div class="col-md-7">
                        <strong>Soci&#xEB;teit de Beuk
                            <br> Eerste Barendrechtseweg 53-55<br> 2991XE<br> Barendrecht<br>
                        </strong>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr>Door ondertekening van dit formulier geeft u Soci&#xEB;teit De Beuk toestemming om doorlopende incasso-opdrachten
                    te sturen naar uw bank om een bedrag van uw rekening af te schrijven wegens de jaarlijkse contributie
                    en geeft u uw bank toestemming om doorlopend een bedrag van uw rekening af te schrijven overeenkomstig
                    de opdracht van Soci&#xEB;teit De Beuk. Als u het niet eens bent met deze afschrijving, kunt u deze laten
                    terugboeken. Neem hiervoor binnen acht weken na de afschrijving contact op met uw bank. Vraag uw bank
                    naar de voorwaarden.<hr>
                </div>
                <div class="col-md-12 form-inline">
                    <div class="form-group">Naam
                        <input type="text" class="form-control" autocomplete="given-name" disabled>
                    </div>
                    <br>
                    <div class="form-group">Adres
                        <input type="text" class="form-control" autocomplete="street-address" disabled>
                    </div>
                    <br>
                    <div class="form-group">Postcode
                        <input type="text" class="form-control" autocomplete='postal-code' disabled>
                    </div>
                    <br>
                    <div class="form-group">Woonplaats
                        <input type="text" class="form-control" autocomplete="address-level2" disabled>
                    </div>
                    <br>

                    <br>
                    <div class="form-group">Plaats en datum
                        <input type="text" class="form-control" disabled>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <p>* = verplicht veld.</p>
        <div class="form-group row">
            <div class="col-md-6">
                <label class="col-form-label">&#xA0;</label>
                <input type="hidden" name="id" value="<?php if ($pageType === 'edit') { echo $row ['id']; } ?>">

                <input type="submit" <?php if ($pageType === 'edit') { echo 'name="saveMember"'; } else { echo 'name="saveNewMember"'; }?> class="btn btn-sm btn-primary" value="Opslaan">
                <a href="index.php" class="btn btn-sm btn-danger">Annuleren</a>
            </div>
        </div>
    </div>
</div>
</form>
