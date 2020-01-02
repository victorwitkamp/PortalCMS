<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PortalCMS\Core\Database\DB;

class RolePermissionMapper
{

    /**
     * Returns the permissions of a role as an array
     *
     * @param string $role_id
     *
     * @return mixed
     */
    public static function getRolePermissions(int $role_id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT t2.perm_id, t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id = ?
                                ORDER BY perm_id'
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * Returns the permissions of a role as an array
     *
     * @param string $role_id
     *
     * @return mixed
     */
    public static function getRoleSelectablePermissions(int $role_id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT * FROM permissions where perm_id not in (
            SELECT perm_id
                FROM role_perm
                    WHERE role_id = ?)
                        ORDER BY perm_id'
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }


    /**
     * Check whether a role has a specific permission
     *
     * @param string $role_id
     * @param string $perm_desc
     *
     * @return bool
     */
    public static function isAssigned(int $role_id, string $perm_desc): bool
    {
        $stmt = DB::conn()->prepare(
            'SELECT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id = ? and t2.perm_desc = ?'
        );
        $stmt->execute([$role_id, $perm_desc]);
        return ($stmt->rowCount() === 1);
    }

    public static function assign(int $role_id, int $perm_id): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO role_perm(role_id, perm_id) VALUES (?,?)'
        );
        if ($stmt->execute([$role_id, $perm_id])) {
            return true;
        }
        return false;
    }

    public static function unassign(int $role_id, int $perm_id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM role_perm
                        where role_id=?
                        AND perm_id=?
                        LIMIT 1'
        );
        $stmt->execute([$role_id, $perm_id]);
        return $stmt->rowCount() === 1;
    }
}
