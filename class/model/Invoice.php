<?php

class Invoice
{
    /**
     * Checks if an invoice with a specific factuurnummer exists
     *
     * @param $id int The id of the invoice
     *
     * @return bool
     */
    public static function factuurnummerExists($factuurnummer)
    {
        $stmt = DB::conn()->prepare(
            "SELECT id
                    FROM invoices
                        WHERE factuurnummer = :factuurnummer
                        LIMIT 1"
        );
        $stmt->execute(
            array(
                ':factuurnummer' => $factuurnummer
            )
        );
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }







    public static function create($contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum)
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO invoices(id, contract_id, factuurnummer, year, month, factuurdatum, vervaldatum)
            VALUES (NULL,?,?,?,?,?,?)"
        );
        $stmt->execute([$contract_id, $factuurnummer, $year, $month, $factuurdatum, $vervaldatum]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices WHERE id = ? limit 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        }
        return $stmt->fetch();
    }

    public static function getByFactuurnummer($factuurnummer)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM invoices
                    WHERE factuurnummer = ?
                    LIMIT 1"
        );
        $stmt->execute([$factuurnummer]);
        if (!$stmt->rowCount() == 1) {
            return false;
        }
        return $stmt->fetch();
    }

    public static function getAllInvoices()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices");
        $stmt->execute();
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function displayInvoiceSumById($id) {
        $sum = self::getInvoiceSumById($id);
        if (!$sum) {
            return false;
        }
        return '&euro; '.$sum;
    }

    public static function getInvoiceSumById($id)
    {
        $sum = 0;
        $invoiceitems = InvoiceItem::getByInvoiceId($id);
        foreach ($invoiceitems as $row) {
            $sum = $sum + $row['price'];
        }
        return $sum;
    }

    public static function getInvoicesByContractId($contractId)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices where contract_id = ?");
        $stmt->execute([$contractId]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

}
