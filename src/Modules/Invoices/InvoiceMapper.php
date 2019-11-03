<?php

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\Database\DB;

class InvoiceMapper
{
    /**
     * Delete an Invoice by Id.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function delete($id): bool
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

    public static function getById($id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM invoices WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function getByFactuurnummer($factuurnummer)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                    FROM invoices
                    WHERE factuurnummer = ?
                    LIMIT 1'
        );
        $stmt->execute([$factuurnummer]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function getByContractId($contractId)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM invoices where contract_id = ?');
        $stmt->execute([$contractId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }

    public static function getAll()
    {
        $stmt = DB::conn()->query('SELECT * FROM invoices');
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }

    public static function create($contract_id, $factuurnummer, $year, $month, $factuurdatum): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO invoices(id, contract_id, factuurnummer, year, month, factuurdatum, vervaldatum)
            VALUES (NULL,?,?,?,?,?,NULL)'
        );
        // $stmt->execute([$contract_id, $factuurnummer, $year, $month, $factuurdatum]);

        // todo: iets met vervaldatum doen.
        // voor nu dezelfde waarde als factuurdatum
        $stmt->execute([$contract_id, $factuurnummer, $year, $month, $factuurdatum]);

        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateMailId($invoice_id, $mail_id): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE invoices SET mail_id = ? WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$mail_id, $invoice_id]);
        return $stmt->rowCount() === 1;
    }

    public static function updateStatus($invoice_id, $status): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE invoices SET status = ? WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$status, $invoice_id]);
        return $stmt->rowCount() === 1;
    }
}
