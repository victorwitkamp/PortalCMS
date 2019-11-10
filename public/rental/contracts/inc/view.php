<?php

use PortalCMS\Core\View\Text;

?>
<div class="row">
    <div class="col-md-8">
        <div class="form-group row">
            <div class="col-md-8">
                <label class="col-form-label">Huurder</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->band_naam ?>" required
                       disabled>
            </div>
            <div class="col-md-4">
                <label class="col-form-label">bandcode</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->bandcode ?>" required
                       disabled>
            </div>
        </div>
        <h3>Contract datum</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label class="col-form-label">Ingangsdatum</label>
                    <div class="form-group date" id="datetimepicker2" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm  datetimepicker-input"
                                   data-target="#datetimepicker2" value="<?= $contract->contract_ingangsdatum ?>"
                                   disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Einddatum</label>
                    <div class="form-group date" id="datetimepicker3" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm  datetimepicker-input"
                                   data-target="#datetimepicker3" value="<?= $contract->contract_einddatum ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Contractdatum</label>
                    <div class="form-group date" id="datetimepicker4" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm  datetimepicker-input"
                                   data-target="#datetimepicker4" value="<?= $contract->contract_datum ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3>Gegevens bandleider</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">Naam</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_naam ?>"
                           disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="col-form-label">Adres</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_adres ?>"
                           disabled>
                </div>
                <div class="col-md-2">
                    <label class="col-form-label">Postcode</label>
                    <input type="text" class="form-control form-control-sm" maxlength="6"
                           value="<?= $contract->bandleider_postcode ?>" disabled>
                </div>

                <div class="col-md-4">
                    <label class="col-form-label">Woonplaats</label>
                    <input type="text" class="form-control form-control-sm"
                           value="<?= $contract->bandleider_woonplaats ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">Geboortedatum</label>
                    <div class="form-group date" id="datetimepicker1" data-target-input="nearest">
                        <div class="input-group">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"
                                   value="<?= $contract->bandleider_geboortedatum ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="col-form-label">Telefoonnummer 1</label>
                    <input type="text" class="form-control form-control-sm"
                           value="<?= $contract->bandleider_telefoonnummer1 ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">Telefoonnummer 2</label>
                    <input type="text" class="form-control form-control-sm"
                           value="<?= $contract->bandleider_telefoonnummer2 ?>" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">E-mail</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_email ?>"
                           disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-form-label">BSN</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $contract->bandleider_bsn ?>"
                           disabled>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label class="col-form-label">beuk_vertegenwoordiger</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->beuk_vertegenwoordiger ?>"
                       disabled>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h3>Oefenruimte</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">Oefenruimte nr.</div>
                <div class="col-sm-6"><?= $contract->huur_oefenruimte_nr ?></div>
            </div>
            <div class="row">
                <div class="col-sm-6"><?= Text::get('DAY') ?></div>
                <div class="col-sm-6"><?= $contract->huur_dag ?></div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label class="col-form-label">huur_start</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->huur_start ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="col-form-label">huur_eind</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->huur_einde ?>" disabled>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label class="col-form-label">huur_kast_nr</label>
                <input type="text" class="form-control form-control-sm" value="<?= $contract->huur_kast_nr ?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <h3>Kosten</h3>
            <div class="row">
                <div class="col-md-3">
                    <label class="col-form-label">Ruimte (per maand)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   value="<?= $contract->kosten_ruimte ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Kast (per maand)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   value="<?= $contract->kosten_kast ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Totaal (per maand)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   value="<?= $contract->kosten_totaal ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Borg (eenmalig)</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   value="<?= $contract->kosten_borg ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>