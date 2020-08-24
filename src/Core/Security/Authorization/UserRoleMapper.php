<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class UserRoleMapper
 * @package PortalCMS\Core\Security\Authorization
 */
class UserRoleMapper
{
    /**
     */
    public static function getByUserId(int $userId): ?array
    {
        $stmt = Database::conn()->prepare('SELECT t1.role_id, t2.role_name
                FROM user_role as t1
                    JOIN roles t2 on t1.role_id = t2.role_id
                        where t1.user_id = ?
                            ORDER BY t1.role_id');
        $stmt->execute([$userId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     */
    public static function isAssigned(int $user_id, int $role_id): bool
    {
        $stmt = Database::conn()->prepare('SELECT *
                    FROM user_role
                        WHERE user_id = ?
                            and role_id = ?
                                LIMIT 1');
        $stmt->execute([$user_id, $role_id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     */
    public static function assign(int $user_id, int $role_id): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO user_role (user_id, role_id)
                    VALUES (?,?)');
        if ($stmt->execute([$user_id, $role_id])) {
            return true;
        }
        return false;
    }

    /**
     */
    public static function unassign(int $user_id, int $role_id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM user_role
                    where user_id=?
                        and role_id=?');
        if ($stmt->execute([$user_id, $role_id])) {
            return true;
        }
        return false;
    }
}
