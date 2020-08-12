<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PDO;
use PortalCMS\Core\Database\Database;

class InvoiceMapper
{
    public static function delete(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM invoices WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function getById(int $id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM invoices WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public static function getByFactuurnummer(string $factuurnummer): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM invoices WHERE factuurnummer = ? LIMIT 1');
        $stmt->execute([ $factuurnummer ]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public static function getByContractId(int $contractId): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM invoices where contract_id = ?');
        $stmt->execute([ $contractId ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getByContractIdAndYear(int $contractId, int $year): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM invoices where contract_id = ? AND year = ?');
        $stmt->execute([ $contractId, $year ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getAll(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM invoices ORDER BY factuurnummer');
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getByYear(int $year): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM invoices WHERE year = ? ORDER BY factuurnummer');
        $stmt->execute([ $year ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getInvoiceCountByContractIdAndYear(int $contractId, int $year): int
    {
        $stmt = Database::conn()->prepare('SELECT id FROM invoices WHERE contract_id = ? AND year = ?');
        $stmt->execute([ $contractId, $year ]);
        return $stmt->rowCount();
    }

    public static function getInvoiceCountByYear(int $year): int
    {
        $stmt = Database::conn()->prepare('SELECT id FROM invoices WHERE year = ?');
        $stmt->execute([ $year ]);
        return $stmt->rowCount();
    }

    public static function getInvoiceCount(): int
    {
        $stmt = Database::conn()->prepare('SELECT id FROM invoices');
        $stmt->execute([]);
        return $stmt->rowCount();
    }

    public static function getYears(): array
    {
        $stmt = Database::conn()->query('SELECT distinct year FROM invoices');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(int $contract_id, string $factuurnummer, int $year, int $month, string $factuurdatum): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO invoices(id, contract_id, factuurnummer, year, month, factuurdatum, vervaldatum)
            VALUES (NULL,?,?,?,?,?,NULL)');
        return ($stmt->execute([ $contract_id, $factuurnummer, $year, $month, $factuurdatum ]));
    }

    public static function updateMailId(int $invoice_id, int $mail_id): bool
    {
        $stmt = Database::conn()->prepare('UPDATE invoices SET mail_id = ? WHERE id = ? LIMIT 1');
        $stmt->execute([ $mail_id, $invoice_id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateStatus(int $invoice_id, int $status): bool
    {
        $stmt = Database::conn()->prepare('UPDATE invoices SET status = ? WHERE id = ? LIMIT 1');
        $stmt->execute([ $status, $invoice_id ]);
        return ($stmt->rowCount() === 1);
    }
}
