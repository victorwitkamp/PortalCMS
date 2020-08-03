<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

use PDO;
use PortalCMS\Core\Database\Database;

class ContractMapper
{
    public static function get(): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM contracts ORDER BY id');
        $stmt->execute([]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getById(int $Id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM contracts WHERE id = ? LIMIT 1');
        $stmt->execute([$Id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function new(Contract $contract): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO contracts (
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
            )');
        $stmt->execute([
            $contract->accountManager, $contract->name, $contract->code, $contract->contractContact->name, $contract->contractContact->address,
            $contract->contractContact->zipCode, $contract->contractContact->city, $contract->contractContact->dateOfBirth, $contract->contractContact->phonePrimary, $contract->contractContact->phoneSecodary,
            $contract->contractContact->emailAddress, $contract->contractContact->citizenServiceNumber, $contract->rehearsalRoomNumber, $contract->day, $contract->startTime, $contract->endTime, $contract->storageNumber,
            $contract->rehearsalRoomMonthlyCost, $contract->storageMonthlyCost, $contract->totalMonthlyCost, $contract->bailCost, $contract->startDate, $contract->endDate, $contract->contractDate
        ]);
        return ($stmt->rowCount() === 1);
    }

    public static function lastInsertedId(): ?int
    {
        $id = Database::conn()->query('SELECT max(id) from contracts')->fetchColumn();
        if (!empty($id) && is_numeric($id)) {
            return (int) $id;
        }
        return null;
    }

    public static function update(Contract $contract): bool
    {
        $stmt = Database::conn()->prepare(
            'UPDATE contracts
                    SET
                    beuk_vertegenwoordiger=?, band_naam=?, bandcode=?, bandleider_naam=?, bandleider_adres=?, bandleider_postcode=?,
                    bandleider_woonplaats=?, bandleider_geboortedatum=?, bandleider_telefoonnummer1=?, bandleider_telefoonnummer2=?,
                    bandleider_email=?, bandleider_bsn=?, huur_oefenruimte_nr=?, huur_dag=?, huur_start=?, huur_einde=?, huur_kast_nr=?,
                    kosten_ruimte=?, kosten_kast=?, kosten_totaal=?, kosten_borg=?, contract_ingangsdatum=?, contract_einddatum=?, contract_datum=?
                    WHERE id=?'
        );
        $stmt->execute([
            $contract->accountManager, $contract->name, $contract->code, $contract->contractContact->name, $contract->contractContact->address,
            $contract->contractContact->zipCode, $contract->contractContact->city, $contract->contractContact->dateOfBirth, $contract->contractContact->phonePrimary, $contract->contractContact->phoneSecodary,
            $contract->contractContact->emailAddress, $contract->contractContact->citizenServiceNumber, $contract->rehearsalRoomNumber, $contract->day, $contract->startTime, $contract->endTime, $contract->storageNumber,
            $contract->rehearsalRoomMonthlyCost, $contract->storageMonthlyCost, $contract->totalMonthlyCost, $contract->bailCost, $contract->startDate, $contract->endDate, $contract->contractDate, $contract->id
        ]);
        return ($stmt->rowCount() === 1);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM contracts WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }
}
