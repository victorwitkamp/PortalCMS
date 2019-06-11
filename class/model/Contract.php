<?php

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */

class Contract
{
    public static function new()
    {
        $beuk_vertegenwoordiger     = Request::post('beuk_vertegenwoordiger', true);
        $band_naam                  = Request::post('band_naam', true);
        $bandcode                   = Request::post('bandcode', true);
        $bandleider_naam            = Request::post('bandleider_naam', true);
        $bandleider_adres           = Request::post('bandleider_adres', true);
        $bandleider_postcode        = Request::post('bandleider_postcode', true);
        $bandleider_woonplaats      = Request::post('bandleider_woonplaats', true);
        $bandleider_geboortedatum   = Request::post('bandleider_geboortedatum', true);
        $bandleider_telefoonnummer1 = Request::post('bandleider_telefoonnummer1', true);
        $bandleider_telefoonnummer2 = Request::post('bandleider_telefoonnummer2', true);
        $bandleider_email           = Request::post('bandleider_email', true);
        $bandleider_bsn             = Request::post('bandleider_bsn', true);
        $huur_oefenruimte_nr        = Request::post('huur_oefenruimte_nr', true);
        $huur_dag                   = Request::post('huur_dag', true);
        $huur_start                 = Request::post('huur_start', true);
        $huur_einde                 = Request::post('huur_einde', true);
        $huur_kast_nr               = Request::post('huur_kast_nr', true);
        $kosten_ruimte              = Request::post('kosten_ruimte', true);
        $kosten_kast                = Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        $kosten_borg                = Request::post('kosten_borg', true);
        $contract_ingangsdatum      = Request::post('contract_ingangsdatum', true);
        $contract_einddatum         = Request::post('contract_einddatumm', true);
        $contract_datum             = Request::post('contract_datum', true);

        if (!ContractMapper::new(
            $beuk_vertegenwoordiger,
            $band_naam,
            $bandcode,
            $bandleider_naam,
            $bandleider_adres,
            $bandleider_postcode,
            $bandleider_woonplaats,
            $bandleider_geboortedatum,
            $bandleider_telefoonnummer1,
            $bandleider_telefoonnummer2,
            $bandleider_email,
            $bandleider_bsn,
            $huur_oefenruimte_nr,
            $huur_dag,
            $huur_start,
            $huur_einde,
            $huur_kast_nr,
            $kosten_ruimte,
            $kosten_kast,
            $kosten_totaal,
            $kosten_borg,
            $contract_ingangsdatum,
            $contract_einddatum,
            $contract_datum
        )) {
            Session::add('feedback_negative', "Toevoegen van contract mislukt.");
            Redirect::to("rental/contracts/");
        } else {
            Session::add('feedback_positive', "Contract toegevoegd.");
            Redirect::to("rental/contracts/");
        }
    }

    public static function update()
    {
        $Id                         = Request::post('id', true);
        $beuk_vertegenwoordiger     = Request::post('beuk_vertegenwoordiger', true);
        $band_naam                  = Request::post('band_naam', true);
        $bandcode                   = Request::post('bandcode', true);
        $bandleider_naam            = Request::post('bandleider_naam', true);
        $bandleider_adres           = Request::post('bandleider_adres', true);
        $bandleider_postcode        = Request::post('bandleider_postcode', true);
        $bandleider_woonplaats      = Request::post('bandleider_woonplaats', true);
        $bandleider_geboortedatum   = Request::post('bandleider_geboortedatum', true);
        $bandleider_telefoonnummer1 = Request::post('bandleider_telefoonnummer1', true);
        $bandleider_telefoonnummer2 = Request::post('bandleider_telefoonnummer2', true);
        $bandleider_email           = Request::post('bandleider_email', true);
        $bandleider_bsn             = Request::post('bandleider_bsn', true);
        $huur_oefenruimte_nr        = Request::post('huur_oefenruimte_nr', true);
        $huur_dag                   = Request::post('huur_dag', true);
        $huur_start                 = Request::post('huur_start', true);
        $huur_einde                 = Request::post('huur_einde', true);
        $huur_kast_nr               = Request::post('huur_kast_nr', true);
        $kosten_ruimte              = Request::post('kosten_ruimte', true);
        $kosten_kast                = Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        $kosten_borg                = Request::post('kosten_borg', true);
        $contract_ingangsdatum      = Request::post('contract_ingangsdatum', true);
        $contract_einddatum         = Request::post('contract_einddatumm', true);
        $contract_datum             = Request::post('contract_datum', true);

        if (!ContractMapper::exists($Id)) {
            Session::add('feedback_negative', "Wijzigen van contract mislukt.<br>Contract bestaat niet.");
            Redirect::to("rental/contracts/");
        }
        if (!ContractMapper::update(
            $Id,
            $beuk_vertegenwoordiger,
            $band_naam, $bandcode,
            $bandleider_naam, $bandleider_adres, $bandleider_postcode, $bandleider_woonplaats, $bandleider_geboortedatum,
            $bandleider_telefoonnummer1, $bandleider_telefoonnummer2, $bandleider_email, $bandleider_bsn,
            $huur_oefenruimte_nr, $huur_dag, $huur_start, $huur_einde, $huur_kast_nr,
            $kosten_ruimte, $kosten_kast, $kosten_totaal, $kosten_borg, $contract_ingangsdatum,
            $contract_einddatum, $contract_datum)
        ) {
            Session::add('feedback_negative', "Wijzigen van contract mislukt.");
            Redirect::to("rental/contracts/");
        } else {
            Session::add('feedback_positive', "Contract gewijzigd.");
            Redirect::to("rental/contracts/");
        }
    }

    public static function delete()
    {
        $contract_id = Request::post('id', true);
        if (ContractMapper::exists($contract_id)) {
            if(!Invoice::getByContractId($contract_id)) {
                if (ContractMapper::delete($contract_id)) {
                    Session::add('feedback_positive', 'Contract verwijderd.');
                    Redirect::contracts();
                    return true;
                }
                Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
                Redirect::contracts();
                return false;
            } else {
                Session::add('feedback_negative', 'Dit contract heeft al facturen.');
                Redirect::contracts();
                return false;
            }
        }
        Session::add('feedback_negative', 'Verwijderen van contract mislukt.<br>Contract bestaat niet.');
        Redirect::contracts();
        return false;
    }



}