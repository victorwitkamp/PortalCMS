<?php
declare(strict_types=1);


namespace App\Modules\Bank;

use PDO;
use App\Core\Database\Database;

class TransactionImportMapper
{
    public static function create(string $path): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO transaction_imports(`id`,`path`,`processed`) VALUES (null,?,0)');
        return $stmt->execute([$path]);
    }

    public static function getAll(): ?array
    {
        $stmt = Database::conn()->query('SELECT
                         * FROM transaction_imports ORDER BY id');
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : null;
    }

    public static function getById(int $id)
    {
        $stmt = Database::conn()->prepare('SELECT * FROM transaction_imports WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() > 0) ? $stmt->fetchObject() : null;
    }

    public static function setProcessed(int $id): bool
    {
        $stmt = Database::conn()->prepare('UPDATE transaction_imports SET processed = 1 WHERE id = ? LIMIT 1');
        return $stmt->execute([$id]);
    }
}
