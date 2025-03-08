<?php
declare(strict_types=1);


namespace App\Modules\Bank;

use PDO;
use App\Core\Database\Database;

class TransactionContactMapper
{
    public static function getAll(): ?array
    {
        $stmt = Database::conn()->query('SELECT
                         * FROM transaction_contacts ORDER BY name');
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function doesExist(string $iban, string $name): ?int
    {
        $stmt = Database::conn()->prepare('SELECT
                         id FROM transaction_contacts WHERE `iban` = ? AND `name` = ? LIMIT 1');
        $stmt->execute([ $iban,$name ]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchColumn() : null;
    }

    public static function create(string $iban, string $name): bool
    {
        $stmt = Database::conn()->prepare('
            INSERT INTO transaction_contacts(id, iban, name) VALUES (NULL,?,?)
        ');
        return ($stmt->execute([ $iban, $name ]));
    }

    public static function lastInsertedId(): ?int
    {
        $id = Database::conn()->query('SELECT max(id) from transaction_contacts')->fetchColumn();
        if (!empty($id) && is_numeric($id)) {
            return (int)$id;
        }
        return null;
    }
}
