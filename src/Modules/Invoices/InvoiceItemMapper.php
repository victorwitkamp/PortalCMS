<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PDO;
use PortalCMS\Core\Database\DB;

class InvoiceItemMapper
{
    /**
     * Get the Invoice Items for a specific Invoice Id
     * @param int $invoiceId
     * @return mixed
     */
    public static function getByInvoiceId(int $invoiceId) : ?array
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM invoice_items
                WHERE invoice_id = ?'
        );
        $stmt->execute([$invoiceId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create an InvoiceItem with a specific name and price exists for a specific invoiceId.
     * @param $invoiceId
     * @param $name
     * @param $price
     * @return bool
     */
    public static function create(int $invoiceId, string $name, int $price): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO invoice_items(
                id, invoice_id, name, price
                )
                VALUES (NULL,?,?,?)'
        );
        $stmt->execute([$invoiceId, $name, $price]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * Delete an InvoiceItem by Id.
     * @param $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE
                FROM invoice_items
                    WHERE id = ?'
        );
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Delete an InvoiceItem by InvoiceId.
     * @param $id
     * @return bool
     */
    public static function deleteByInvoiceId(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE
                FROM invoice_items
                    WHERE invoice_id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Check if an InvoiceItem with a specific id exists.
     * @param $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'SELECT id
                    FROM invoice_items
                        WHERE id = ?
                        LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    // /**
    //  * Check if an InvoiceItem with a specific name exists for a specific invoiceId.
    //  *
    //  * @param int $invoiceId
    //  * @param string $name
    //  * @param int $price
    //  *
    //  * @return bool
    //  */
    // public static function itemExists($invoiceId, $name)
    // {
    //     $stmt = DB::conn()->prepare(
    //         "SELECT id
    //                 FROM invoice_items
    //                     WHERE invoice_id = ?
    //                     AND name = ?
    //                     LIMIT 1");
    //     $stmt->execute([$invoiceId, $name]);
    //     if ($stmt->rowCount() === 0) {
    //         return false;
    //     }
    //     return true;
    // }

    // /**
    //  * Check if an InvoiceItem with a specific name exists for a specific invoiceId.
    //  *
    //  * @param int $invoiceId
    //  * @param string $name
    //  * @param int $price
    //  *
    //  * @return bool
    //  */
    // public static function itemExists($invoiceId, $name)
    // {
    //     $stmt = DB::conn()->prepare(
    //         "SELECT id
    //                 FROM invoice_items
    //                     WHERE invoice_id = ?
    //                     AND name = ?
    //                     LIMIT 1");
    //     $stmt->execute([$invoiceId, $name]);
    //     if ($stmt->rowCount() === 0) {
    //         return false;
    //     }
    //     return true;
    // }
}
