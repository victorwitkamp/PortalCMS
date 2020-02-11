<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Security\Authorization;

use PDO;
use PortalCMS\Core\Database\DB;

class RoleMapper
{
    /**
     * Returns an array of all roles
     * @return array|null
     */
    public static function getRoles() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM roles ORDER BY role_id ');
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function get(int $role_id) : ?object
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                    FROM roles
                        WHERE role_id = ?
                            LIMIT 1'
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function create(string $role_name): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO roles (role_name) 
                        VALUES (?)'
        );
        $stmt->execute([$role_name]);
        return ($stmt->rowCount() === 1);
    }

    public static function delete(int $role_id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM roles
                        WHERE role_id = ?
                            LIMIT 1'
        );
        $stmt->execute([$role_id]);
        return ($stmt->rowCount() === 1);
    }
}
