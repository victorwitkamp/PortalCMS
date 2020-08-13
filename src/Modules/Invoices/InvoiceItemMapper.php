<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class InvoiceItemMapper
 * @package PortalCMS\Modules\Invoices
 */
class InvoiceItemMapper
{
    /**
     * @param int $invoiceId
     * @return array|null
     */
    public static function getByInvoiceId(int $invoiceId): ?array
    {
        $stmt = Database::conn()->prepare('SELECT *
                FROM invoice_items
                WHERE invoice_id = ?');
        $stmt->execute([ $invoiceId ]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int    $invoiceId
     * @param string $name
     * @param int    $price
     * @return bool
     */
    public static function create(int $invoiceId, string $name, int $price): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO invoice_items(
                id, invoice_id, name, price
                )
                VALUES (NULL,?,?,?)');
        $stmt->execute([ $invoiceId, $name, $price ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE
                FROM invoice_items
                    WHERE id = ?');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function deleteByInvoiceId(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE
                FROM invoice_items
                    WHERE invoice_id = ?');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() > 0);
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $stmt = Database::conn()->prepare('SELECT id
                    FROM invoice_items
                        WHERE id = ?
                        LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }
}
