<?php
declare(strict_types=1);

namespace PortalCMS\Modules\Invoices;

use PortalCMS\Core\Database\DB;

class InvoiceItemMapper
{
    /**
     * Get the Invoice Items for a specific Invoice Id
     *
     * @param int $invoiceId
     *
     * @return mixed
     */
    public static function getByInvoiceId($invoiceId)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM invoice_items
                WHERE invoice_id = ?'
        );
        $stmt->execute([$invoiceId]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * Create an InvoiceItem with a specific name and price exists for a specific invoiceId.
     *
     * @param int    $invoiceId
     * @param string $name
     * @param int    $price
     *
     * @return bool
     */
    public static function create($invoiceId, $name, $price): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO invoice_items(id, invoice_id, name, price)
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
     *
     * @param int $id
     *
     * @return bool
     */
    public static function delete($id): bool
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
     *
     * @param int $id
     *
     * @return bool
     */
    public static function deleteByInvoiceId($id): bool
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
     *
     * @param int $id
     *
     * @return bool
     */
    public static function exists($id): bool
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
