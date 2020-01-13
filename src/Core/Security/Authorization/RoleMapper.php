<?php
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
    public static function getRoles(): ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM roles ORDER BY role_id ');
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * Returns a role object by it's ID
     * @param string $role_id
     * @return mixed
     */
    public static function get(int $role_id)
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

    /**
     * Create a new role
     *
     * @param string $role_name
     * @return bool
     */
    public static function create(string $role_name): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO roles
                        (role_name)
                        VALUES (?)'
        );
        if ($stmt->execute([$role_name])) {
            return true;
        }
        return false;
    }

    /**
     * Delete an existing role
     *
     * @param int $role_id
     * @return bool
     */
    public static function delete(int $role_id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM roles
                        where role_id=?
                        LIMIT 1'
        );
        if ($stmt->execute([$role_id])) {
            return true;
        }
        return false;
    }
}
