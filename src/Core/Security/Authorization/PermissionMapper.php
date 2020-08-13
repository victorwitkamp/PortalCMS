<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Security\Authorization;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class PermissionMapper
 * @package PortalCMS\Core\Security\Authorization
 */
class PermissionMapper
{
    /**
     * @param int $perm_id
     * @return bool|mixed
     */
    public static function getById(int $perm_id)
    {
        $stmt = Database::conn()->prepare('SELECT *
                    FROM permissions
                        WHERE perm_id = ?
                            LIMIT 1');
        $stmt->execute([ $perm_id ]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetch();
    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public static function getPermissionsByUserId(int $user_id): ?array
    {
        $stmt = Database::conn()->prepare('SELECT DISTINCT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id in
                            (SELECT role_id
                                    FROM user_role
                                        WHERE user_id = ?)');
        $stmt->execute([ $user_id ]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
