<?php



declare(strict_types=1);

namespace App\Modules\Bank;

use PDO;
use App\Core\Database\Database;

class TransactionMapper
{
    public static function getSumByCategory(int $year)
    {
        $stmt = Database::conn()->prepare(
            '
            SELECT Sum(Bedrag) 
            FROM transactions
            WHERE transaction_category = ?
            '
        );
        $stmt->execute([ $year ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_COLUMN) : null;
    }

    public static function getSumByCategoryAndYear(int $category, int $year)
    {
        $stmt = Database::conn()->prepare(
            '
            SELECT Sum(Bedrag) 
            FROM transactions
            WHERE transaction_category = ?
            AND YEAR(date(Datum))=?
            '
        );
        $stmt->execute([ $category, $year ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_COLUMN) : null;
    }

    public static function create(array $array, int $id = null): bool
    {
        $stmt = Database::conn()->prepare(query: '
            INSERT INTO transactions(
                `id`, `IBAN/BBAN`, `Munt`, `BIC`, `Volgnr`,
                `Datum`, `Rentedatum`, `Bedrag`, `Saldo na trn`, `Tegenrekening IBAN/BBAN`,
                `Naam tegenpartij`, `Naam uiteindelijke partij`, `Naam initiërende partij`, `BIC tegenpartij`, `Code`,
                `Batch ID`, `Transactiereferentie`, `Machtigingskenmerk`, `Incassant ID`, `Betalingskenmerk`,
                `Omschrijving-1`, `Omschrijving-2`, `Omschrijving-3`, `Reden retour`, `Oorspr bedrag`,
                `Oorspr munt`, `Koers`, `transaction_import_id`, `transaction_contact_id`   
            ) VALUES (
                ?,?,?,?,?,
                ?,?,?,?,?,
                ?,?,?,?,?,
                ?,?,?,?,?,
                ?,?,?,?,?,
                ?,?,?,?
          )
        ');
        return $stmt->execute([
            null,
            $array['IBAN/BBAN'],
            $array['Munt'],
            $array['BIC'],
            $array['Volgnr'],
            $array['Datum'],
            $array['Rentedatum'],
            $array['Bedrag'],
            $array['Saldo na trn'],
            $array['Tegenrekening IBAN/BBAN'],
            $array['Naam tegenpartij'],
            $array['Naam uiteindelijke partij'],
            $array['Naam initiërende partij'],
            $array['BIC tegenpartij'],
            $array['Code'],
            $array['Batch ID'],
            $array['Transactiereferentie'],
            $array['Machtigingskenmerk'],
            $array['Incassant ID'],
            $array['Betalingskenmerk'],
            $array['Omschrijving-1'],
            $array['Omschrijving-2'],
            $array['Omschrijving-3'],
            $array['Reden retour'],
            $array['Oorspr bedrag'],
            $array['Oorspr munt'],
            $array['Koers'],
            $id,
            null
        ]);
    }

    public static function setCategory(int $transactionId = null, int $categoryId = null): bool
    {
        $stmt = Database::conn()->prepare('
            UPDATE transactions
            SET transaction_category = ?
            WHERE id = ?
        ');
        $stmt->execute([ $categoryId, $transactionId ]);
        return ($stmt->rowCount() === 1);
    }

    public static function getAccountNames(): ?array
    {
        $stmt = Database::conn()->query('
                    SELECT `id`,`Naam tegenpartij`,`Tegenrekening IBAN/BBAN`,
                           `transaction_contact_id`
                    FROM transactions 
                    WHERE `transaction_contact_id` IS NULL
                    ORDER BY `Naam tegenpartij`');
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null;
    }

    public static function updateTransactionContactId(int $id, string $iban, string $name): int
    {
        $stmt = Database::conn()->prepare('
                    UPDATE transactions 
                    SET `transaction_contact_id` = ? 
                    WHERE `Tegenrekening IBAN/BBAN` = ? 
                      AND `Naam tegenpartij` = ?
                    AND `transaction_contact_id` IS NULL
                  ');
        $stmt->execute([ $id, $iban, $name ]);
        return $stmt->rowCount();
    }

    public static function getAll(): ?array
    {
        $stmt = Database::conn()->query('
            SELECT
                `id`, `IBAN/BBAN`, `Datum`, `Bedrag`,`Tegenrekening IBAN/BBAN`,
                `Naam tegenpartij`,`Naam uiteindelijke partij`,`Naam initiërende partij`,`Code`,`Batch ID`,
                `Transactiereferentie`,`Machtigingskenmerk`,`Incassant ID`,`Betalingskenmerk`,
                `Omschrijving-1`,`Omschrijving-2`,`Omschrijving-3`,
                `Reden retour`,`Oorspr bedrag`,`transaction_import_id`,`transaction_contact_id`,`transaction_category`
            FROM transactions
            WHERE `transaction_category` is null
            ORDER BY id');
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getByImportId(int $id): ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT
                `id`, `IBAN/BBAN`, `Datum`, `Bedrag`,`Tegenrekening IBAN/BBAN`,
                `Naam tegenpartij`,`Naam uiteindelijke partij`,`Naam initiërende partij`,`Code`,`Batch ID`,
                `Transactiereferentie`,`Machtigingskenmerk`,`Incassant ID`,`Betalingskenmerk`,
                `Omschrijving-1`,`Omschrijving-2`,`Omschrijving-3`,
                `Reden retour`,`Oorspr bedrag`,`transaction_import_id`,`transaction_contact_id`,`transaction_category`
            FROM transactions 
            WHERE transaction_import_id = ?
            ORDER BY id');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getToProcess(int $importid): ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT
                `Tegenrekening IBAN/BBAN`,
                `Naam tegenpartij`
            FROM transactions 
            WHERE transaction_import_id = ?
            AND `transaction_contact_id` is null
            ORDER BY id');
        $stmt->execute([ $importid ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null;
    }
}
