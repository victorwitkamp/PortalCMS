<?php
class ContractMapper
{
    public static function get()
    {
        $stmt = DB::conn()->prepare(
            "SELECT * FROM contracts ORDER BY id"
        );
        $stmt->execute([]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function exists($Id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT id FROM contracts WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$Id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function getById($Id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT * FROM contracts WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$Id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function new(
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
                bandleider_naam, bandleider_adres, bandleider_postcode, bandleider_woonplaats, bandleider_geboortedatum,
                bandleider_telefoonnummer1, bandleider_telefoonnummer2, bandleider_email, bandleider_bsn,
                huur_oefenruimte_nr, huur_dag, huur_start, huur_einde, huur_kast_nr,
                kosten_ruimte, kosten_kast, kosten_totaal, kosten_borg,
                contract_ingangsdatum, contract_einddatum, contract_datum
            ) VALUES (
                NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
            )"
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
        return true;
    }

    public static function update(
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
        return true;
    }

    public static function delete($id)
    {
        $stmt = DB::conn()->prepare("DELETE FROM contracts WHERE id = ?");
        if ($stmt->execute([$id])) {
            return true;
        }
        return false;
    }
}
