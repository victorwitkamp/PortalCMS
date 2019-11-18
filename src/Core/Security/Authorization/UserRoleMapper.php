<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PDO;
use PortalCMS\Core\Database\DB;

class UserRoleMapper
{
    public static function getByUserId($userId) : ?array
    {
        $stmt = DB::conn()->prepare(
            'SELECT t1.role_id, t2.role_name
                FROM user_role as t1
                    JOIN roles t2 on t1.role_id = t2.role_id
                        where t1.user_id = ?
                            ORDER BY t1.role_id'
        );
        $stmt->execute([$userId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }


    /**
     * Check whether a user has a specific role
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function isAssigned($user_id, $role_id): bool
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                    FROM user_role
                        WHERE user_id = ?
                            and role_id = ?
                                LIMIT 1'
        );
        $stmt->execute([$user_id, $role_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * Assign a role to a user
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function assign($user_id, $role_id): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO user_role (user_id, role_id)
                    VALUES (?,?)'
        );
        if ($stmt->execute([$user_id, $role_id])) {
            return true;
        }
        return false;
    }

    /**
     * Unassign a role from a user
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function unassign($user_id, $role_id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM user_role
                    where user_id=?
                        and role_id=?'
        );
        if ($stmt->execute([$user_id, $role_id])) {
            return true;
        }
        return false;
    }
}
