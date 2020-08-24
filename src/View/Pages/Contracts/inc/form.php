<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<form method="post">
    <div class="row">
        <div class="col-md-12">

            <div class="form-group">
                <h3>Algemeen</h3>
                <div class="row">
                    <div class="col-md-8">
                        <label class="col-form-label">Huurder</label>
                        <input type="text" name="band_naam"
                               class="form-control" <?= ($pageType === 'edit' && !empty($contract->band_naam)) ? 'value="' . $contract->band_naam . '"' : '' ?>
                               required>
                    </div>
                    <div class="col-md-4">
                        <label class="col-form-label">Klantcode</label>
                        <input minlength="1" maxlength="2" type="number" name="bandcode"
                               class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandcode)) ? 'value="' . $contract->bandcode . '"' : '' ?>
                               required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <h3>Kosten</h3>
                <div class="row">
                    <div class="col-md-3">
                        <label class="col-form-label">Ruimte (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-euro-sign"></i></div>
                            </div>
                            <input type="number" name="kosten_ruimte"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->kosten_ruimte)) ? 'value="' . $contract->kosten_ruimte . '"' : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Kast (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-euro-sign"></i></div>
                            </div>
                            <input type="number" name="kosten_kast"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->kosten_kast)) ? 'value="' . $contract->kosten_kast . '"' : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Totaal (per maand)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-euro-sign"></i></div>
                            </div>
                            <input type="number" name="kosten_totaal"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->kosten_totaal)) ? 'value="' . $contract->kosten_totaal . '"' : '' ?>
                                   disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Borg (eenmalig)</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-euro-sign"></i></div>
                            </div>
                            <input type="number" name="kosten_borg"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->kosten_borg)) ? 'value="' . $contract->kosten_borg . '"' : '' ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="col-form-label">Ingangsdatum</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="date"
                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                   name="contract_ingangsdatum"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->contract_ingangsdatum)) ? 'value="' . date('Y-m-d', strtotime($contract->contract_ingangsdatum)) . '"' : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Einddatum</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="date"
                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                   name="contract_einddatum"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->contract_einddatum)) ? 'value="' . date('Y-m-d', strtotime($contract->contract_einddatum)) . '"' : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label">Contractdatum</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="date"
                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                   name="contract_datum"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->contract_datum)) ? 'value="' . date('Y-m-d', strtotime($contract->contract_datum)) . '"' : '' ?>>
                        </div>
                    </div>
                </div>

            </div>

            <h3>Contactpersoon</h3>
            <div class="form-group">

                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Naam</label>
                        <input type="text" name="bandleider_naam"
                               class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_naam)) ? 'value="' . $contract->bandleider_naam . '"' : '' ?>>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Adres</label>
                        <input type="text" name="bandleider_adres" class="form-control"
                               placeholder="Voorbeeldadres 123" <?= ($pageType === 'edit' && !empty($contract->bandleider_adres)) ? 'value="' . $contract->bandleider_adres . '"' : '' ?>>
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">Postcode</label>
                        <input type="text" name="bandleider_postcode" class="form-control" maxlength="6"
                               placeholder="1234AB" <?= ($pageType === 'edit' && !empty($contract->bandleider_postcode)) ? 'value="' . $contract->bandleider_postcode . '"' : '' ?>>
                    </div>
                    <div class="col-md-4">
                        <label class="col-form-label">Woonplaats</label>
                        <input type="text" name="bandleider_woonplaats" class="form-control"
                               placeholder="Barendrecht" <?= ($pageType === 'edit' && !empty($contract->bandleider_woonplaats)) ? 'value="' . $contract->bandleider_woonplaats . '"' : '' ?>>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <label class="col-form-label">Telefoonnummer 1</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
                            </div>
                            <input type="tel" name="bandleider_telefoonnummer1"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_telefoonnummer1)) ? 'value="' . $contract->bandleider_telefoonnummer1 . '"' : '' ?>>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="col-form-label">Telefoonnummer 2</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
                            </div>
                            <input type="tel" name="bandleider_telefoonnummer2"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_telefoonnummer2)) ? 'value="' . $contract->bandleider_telefoonnummer2 . '"' : '' ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">E-mail</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                            </div>
                            <input type="email"
                                   title="The domain portion of the email address is invalid (the portion after the @)."
                                   pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$"
                                   name="bandleider_email"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_email)) ? 'value="' . $contract->bandleider_email . '"' : '' ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="col-form-label">BSN</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                            </div>
                            <input type="text" name="bandleider_bsn"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_bsn)) ? 'value="' . $contract->bandleider_bsn . '"' : '' ?>>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="col-form-label">Geboortedatum</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="date"
                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                   name="bandleider_geboortedatum"
                                   class="form-control" <?= ($pageType === 'edit' && !empty($contract->bandleider_geboortedatum)) ? 'value="' . date('Y-m-d', strtotime($contract->bandleider_geboortedatum)) . '"' : '' ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h3>Oefenruimte</h3>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Ruimte</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr"
                               value="1" <?= ($pageType === 'edit' && !empty($contract->huur_oefenruimte_nr) && $contract->huur_oefenruimte_nr === '1') ? 'checked' : '' ?>>
                        <label class="form-check-label">Oefenruimte 1</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="huur_oefenruimte_nr"
                               value="2" <?= ($pageType === 'edit' && !empty($contract->huur_oefenruimte_nr) && $contract->huur_oefenruimte_nr === '2') ? 'checked' : '' ?>>
                        <label class="form-check-label">Oefenruimte 2</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kast nr.</label>
                <div class="col-sm-10">
                    <input type="text" name="huur_kast_nr"
                           class="form-control" <?= ($pageType === 'edit' && !empty($contract->huur_kast_nr)) ? 'value="' . $contract->huur_kast_nr . '"' : '' ?>
                    ">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?= Text::get('DAY') ?></label>
                <div class="col-sm-10">
                    <select name="huur_dag" class="form-control">
                        <option>Selecteer een dag....</option>
                        <option value="<?= Text::get('DAY_01') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_01')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_01') ?></option>
                        <option value="<?= Text::get('DAY_02') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_02')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_02') ?></option>
                        <option value="<?= Text::get('DAY_03') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_03')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_03') ?></option>
                        <option value="<?= Text::get('DAY_04') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_04')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_04') ?></option>
                        <option value="<?= Text::get('DAY_05') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_05')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_05') ?></option>
                        <option value="<?= Text::get('DAY_06') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_06')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_06') ?></option>
                        <option value="<?= Text::get('DAY_07') ?>" <?= ($pageType === 'edit' && (strtoupper(Text::get('DAY_07')) === strtoupper($contract->huur_dag))) ? 'selected' : '' ?>><?= Text::get('DAY_07') ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Begintijd</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                        <input type="time" name="huur_start"
                               class="form-control" <?= ($pageType === 'edit' && !empty($contract->huur_start)) ? 'value="' . $contract->huur_start . '"' : '' ?>
                        ">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Eindtijd</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                        <input type="time" name="huur_einde"
                               class="form-control"<?= ($pageType === 'edit' && !empty($contract->huur_einde)) ? 'value="' . $contract->huur_einde . '"' : '' ?>
                        ">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">beuk_vertegenwoordiger</label>
                    <input type="text" name="beuk_vertegenwoordiger"
                           class="form-control" <?= ($pageType === 'edit' && !empty($contract->beuk_vertegenwoordiger)) ? 'value="' . $contract->beuk_vertegenwoordiger . '"' : '' ?>
                    ">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr>
            <p>* = verplicht veld.</p>
            <input type="hidden"
                   name="id" <?= ($pageType === 'edit' && !empty($contract->id)) ? 'value="' . $contract->id . '"' : '' ?>>
            <input type="submit" <?= ($pageType === 'edit') ? 'name="updateContract"' : 'name="newContract"' ?>
                   class="btn btn-sm btn-outline-primary" value="Opslaan">
            <a <?= ($pageType === 'edit' && !empty($contract->id)) ? 'href="/Contracts/Details?id=' . $contract->id . '"' : 'href="/Contracts"' ?>
                    class=" btn btn-sm btn-danger">Annuleren</a>
        </div>
    </div>

</form>
