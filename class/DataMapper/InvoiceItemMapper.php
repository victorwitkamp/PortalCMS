<?php

class InvoiceItemMapper
{
    /**
     * Get the Invoice Items for a specific Invoice Id
     *
     * @param int $invoiceId
     *
     * @return array
     */
    public static function getByInvoiceId(int $invoiceId)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                FROM invoice_items
                WHERE invoice_id = ?"
        );
        $stmt->execute([$invoiceId]);
        if (!$stmt->rowCount() > 1) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * Create an InvoiceItem with a specific name and price exists for a specific invoiceId.
     *
     * @param int $invoiceId
     * @param string $name
     * @param int $price
     *
     * @return bool
     */
    public static function create(int $invoiceId, string $name, int $price) {
        $stmt = DB::conn()->prepare(
            "INSERT INTO invoice_items(id, invoice_id, name, price)
            VALUES (NULL,?,?,?)"
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
    public static function delete(int $id) {
        $stmt = DB::conn()->prepare(
            "DELETE
            FROM invoice_items
            WHERE id = ?"
        );
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Check if an InvoiceItem with a specific id exists.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function exists(int $id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT id
                    FROM invoice_items
                        WHERE id = ?
                        LIMIT 1");
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Check if an InvoiceItem with a specific name and price exists for a specific invoiceId.
     *
     * @param int $invoiceId
     * @param string $name
     * @param int $price
     *
     * @return bool
     */
    public static function itemExists(int $invoiceId, string $name, int $price)
    {
        $stmt = DB::conn()->prepare(
            "SELECT id
                    FROM invoice_items
                        WHERE invoice_id = ?
                        AND name = ?
                        AND price = ?
                        LIMIT 1");
        $stmt->execute([$invoiceId, $name, $price]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }
}