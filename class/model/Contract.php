<?php

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */

class Contract
{
    public static function getStartDateById($Id)
    {
        $stmt = DB::conn()->prepare("SELECT contract_ingangsdatum FROM contracts WHERE id = ? limit 1");
        $stmt->execute([$Id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetchColumn();
        }
    }

    public static function getAll()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM contracts ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function doesIdExist($Id)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM contracts WHERE id = ? limit 1");
        $stmt->execute([$Id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return TRUE;
    }

    public static function getById($Id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM contracts WHERE id = ? limit 1");
        $stmt->execute([$Id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function new()
    {
        $beuk_vertegenwoordiger     = Request::post('beuk_vertegenwoordiger', TRUE);
        $band_naam                  = Request::post('band_naam', TRUE);
        $bandcode                   = Request::post('bandcode', TRUE);
        $bandleider_naam            = Request::post('bandleider_naam', TRUE);
        $bandleider_adres           = Request::post('bandleider_adres', TRUE);
        $bandleider_postcode        = Request::post('bandleider_postcode', TRUE);
        $bandleider_woonplaats      = Request::post('bandleider_woonplaats', TRUE);
        $bandleider_geboortedatum   = Request::post('bandleider_geboortedatum', TRUE);
        $bandleider_telefoonnummer1 = Request::post('bandleider_telefoonnummer1', TRUE);
        $bandleider_telefoonnummer2 = Request::post('bandleider_telefoonnummer2', TRUE);
        $bandleider_email           = Request::post('bandleider_email', TRUE);
        $bandleider_bsn             = Request::post('bandleider_bsn', TRUE);
        $huur_oefenruimte_nr        = Request::post('huur_oefenruimte_nr', TRUE);
        $huur_dag                   = Request::post('huur_dag', TRUE);
        $huur_start                 = Request::post('huur_start', TRUE);
        $huur_einde                 = Request::post('huur_einde', TRUE);
        $huur_kast_nr               = Request::post('huur_kast_nr', TRUE);
        $kosten_ruimte              = Request::post('kosten_ruimte', TRUE);
        $kosten_kast                = Request::post('kosten_kast', TRUE);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        $kosten_borg                = Request::post('kosten_borg', TRUE);
        $contract_ingangsdatum      = Request::post('contract_ingangsdatum', TRUE);
        $contract_einddatum         = Request::post('contract_einddatumm', TRUE);
        $contract_datum             = Request::post('contract_datum', TRUE);

        if (!self::newAction(
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

    public static function newAction(
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
    ) {
        $stmt = DB::conn()->prepare(
            "INSERT INTO contracts (
                id,
                beuk_vertegenwoordiger,
                band_naam,
                bandcode,
                bandleider_naam,
                bandleider_adres,
                bandleider_postcode,
                bandleider_woonplaats,
                bandleider_geboortedatum,
                bandleider_telefoonnummer1,
                bandleider_telefoonnummer2,
                bandleider_email,
                bandleider_bsn,
                huur_oefenruimte_nr,
                huur_dag,
                huur_start,
                huur_einde,
                huur_kast_nr,
                kosten_ruimte,
                kosten_kast,
                kosten_totaal,
                kosten_borg,
                contract_ingangsdatum,
                contract_einddatum,
                contract_datum
            ) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute(
            [
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
            ]
        );
        if (!$stmt) {
            return false;
        }
        return TRUE;
    }

    public static function update()
    {
        $Id                         = Request::post('id', TRUE);
        $beuk_vertegenwoordiger     = Request::post('beuk_vertegenwoordiger', TRUE);
        $band_naam                  = Request::post('band_naam', TRUE);
        $bandcode                   = Request::post('bandcode', TRUE);
        $bandleider_naam            = Request::post('bandleider_naam', TRUE);
        $bandleider_adres           = Request::post('bandleider_adres', TRUE);
        $bandleider_postcode        = Request::post('bandleider_postcode', TRUE);
        $bandleider_woonplaats      = Request::post('bandleider_woonplaats', TRUE);
        $bandleider_geboortedatum   = Request::post('bandleider_geboortedatum', TRUE);
        $bandleider_telefoonnummer1 = Request::post('bandleider_telefoonnummer1', TRUE);
        $bandleider_telefoonnummer2 = Request::post('bandleider_telefoonnummer2', TRUE);
        $bandleider_email           = Request::post('bandleider_email', TRUE);
        $bandleider_bsn             = Request::post('bandleider_bsn', TRUE);
        $huur_oefenruimte_nr        = Request::post('huur_oefenruimte_nr', TRUE);
        $huur_dag                   = Request::post('huur_dag', TRUE);
        $huur_start                 = Request::post('huur_start', TRUE);
        $huur_einde                 = Request::post('huur_einde', TRUE);
        $huur_kast_nr               = Request::post('huur_kast_nr', TRUE);
        $kosten_ruimte              = Request::post('kosten_ruimte', TRUE);
        $kosten_kast                = Request::post('kosten_kast', TRUE);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        $kosten_borg                = Request::post('kosten_borg', TRUE);
        $contract_ingangsdatum      = Request::post('contract_ingangsdatum', TRUE);
        $contract_einddatum         = Request::post('contract_einddatumm', TRUE);
        $contract_datum             = Request::post('contract_datum', TRUE);

        if (self::doesIdExist($Id)) {
            if (!self::updateAction(
                $Id,
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
            )
            ) {
                Session::add('feedback_negative', "Wijzigen van contract mislukt.");
                Redirect::to("rental/contracts/");
            } else {
                Session::add('feedback_positive', "Contract gewijzigd.");
                Redirect::to("rental/contracts/");
            }
        } else {
            Session::add('feedback_negative', "Wijzigen van contract mislukt.<br>Contract bestaat niet.");
            Redirect::to("rental/contracts/");
        }
    }

    public static function updateAction(
        $Id,
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
    ) {
        $stmt = DB::conn()->prepare(
            "UPDATE contracts
                    SET
                    beuk_vertegenwoordiger=?,
                    band_naam=?,
                    bandcode=?,
                    bandleider_naam=?,
                    bandleider_adres=?,
                    bandleider_postcode=?,
                    bandleider_woonplaats=?,
                    bandleider_geboortedatum=?,
                    bandleider_telefoonnummer1=?,
                    bandleider_telefoonnummer2=?,
                    bandleider_email=?,
                    bandleider_bsn=?,
                    huur_oefenruimte_nr=?,
                    huur_dag=?,
                    huur_start=?,
                    huur_einde=?,
                    huur_kast_nr=?,
                    kosten_ruimte=?,
                    kosten_kast=?,
                    kosten_totaal=?,
                    kosten_borg=?,
                    contract_ingangsdatum=?,
                    contract_einddatum=?,
                    contract_datum=?
                    WHERE id=?"
        );
        $stmt->execute(
            [
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
                $contract_datum,
                $Id
            ]
        );
        if (!$stmt) {
            return false;
        }
        return TRUE;
    }

    public static function delete()
    {
        $contract_id = Request::post('id', TRUE);
        $stmt = DB::conn()->prepare("SELECT * FROM contracts where id = ?");
        $stmt->execute([$contract_id]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($result);
        if ($count > 0) {
            if(!Invoice::getByContractId($contract_id)) {
                if (self::deleteAction($contract_id)) {
                    Session::add('feedback_positive', 'Contract verwijderd.');
                    return TRUE;
                }
                Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
                return false;
            } else {
                Session::add('feedback_negative', 'Dit contract heeft al facturen.');
                return false;
            }
        }
        Session::add('feedback_negative', 'Verwijderen van contract mislukt.<br>Contract bestaat niet.');
        return false;
    }

    public static function deleteAction($contract_id)
    {
        $stmt = DB::conn()->prepare("DELETE FROM contracts WHERE id = ?");
        if ($stmt->execute([$contract_id])) {
            return TRUE;
        }
        return false;
    }

}