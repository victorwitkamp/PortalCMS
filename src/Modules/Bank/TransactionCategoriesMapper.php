<?php
declare(strict_types=1);

namespace App\Modules\Bank;

use PDO;
use App\Core\Database\Database;

class TransactionCategoriesMapper
{
    public static function getYears(): ?array
    {
        $stmt = Database::conn()->query('SELECT distinct year FROM transaction_category order by year desc');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getCategories(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM transaction_category order by name desc');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getCategoriesWithYear(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM transaction_category WHERE id in (SELECT DISTINCT transaction_category 
FROM transactions
    WHERE  YEAR(date(Datum)) IS NOT NULL
    ) order by name desc');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getUnusedCategories(): ?array
    {
        $stmt = Database::conn()->query('SELECT * FROM transaction_category WHERE id not in (SELECT DISTINCT transaction_category 
FROM transactions
WHERE  YEAR(date(Datum)) IS NOT NULL AND transaction_category is not null
    ) order by name desc');
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getCategoriesByYear(int $year): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * 
            FROM transaction_category 
            WHERE year = ? 
            order by name desc');
        $stmt->execute([ $year ]);
        return ($stmt->rowCount() === 0) ? null : $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function new(string $name = null, int $code = null): bool
    {
        $stmt = Database::conn()->prepare('
            INSERT INTO transaction_category (
                id, name, code
            ) VALUES (
                null, ?, ?        
            )');
        return $stmt->execute([
            $name,
            $code
        ]);
    }
}
