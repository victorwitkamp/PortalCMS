<?php

class InvoiceMapper
{
    public static function getById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices WHERE id = ? LIMIT 1");
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

    public static function getByContractId($contractId)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices where contract_id = ?");
        $stmt->execute([$contractId]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getAll()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM invoices");
        $stmt->execute();
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
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
}