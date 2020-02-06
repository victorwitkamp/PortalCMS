<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PDO;
use PortalCMS\Core\Database\DB;

class InvoiceMapper
{
    /**
     * Delete an Invoice by Id.
     * @param $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE
            FROM invoices
            WHERE id = ?
            LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public static function getById(int $id) : ?object
    {
        $stmt = DB::conn()->prepare('SELECT * FROM invoices WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getByFactuurnummer(string $factuurnummer)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                    FROM invoices
                    WHERE factuurnummer = ?
                    LIMIT 1'
        );
        $stmt->execute([$factuurnummer]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getByContractId(int $contractId) : ?array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM invoices where contract_id = ?');
        $stmt->execute([$contractId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getAll() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM invoices ORDER BY factuurnummer');
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function create(int $contract_id, string $factuurnummer, int $year, int $month, string $factuurdatum): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO invoices(id, contract_id, factuurnummer, year, month, factuurdatum, vervaldatum)
            VALUES (NULL,?,?,?,?,?,NULL)'
        );
        // todo: iets met vervaldatum doen. voor nu dezelfde waarde als factuurdatum
        $stmt->execute([$contract_id, $factuurnummer, $year, $month, $factuurdatum]);

        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateMailId(int $invoice_id, int $mail_id): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE invoices SET mail_id = ? WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$mail_id, $invoice_id]);
        return $stmt->rowCount() === 1;
    }

    public static function updateStatus(int $invoice_id, int $status): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE invoices SET status = ? WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$status, $invoice_id]);
        return $stmt->rowCount() === 1;
    }
}
