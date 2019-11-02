<?php

namespace PortalCMS\Core\Authorization;

use PortalCMS\Core\Database\DB;

class PermissionMapper
{
    public static function getById($perm_id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                    FROM permissions
                        WHERE perm_id = ?
                            LIMIT 1'
        );
        $stmt->execute([$perm_id]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetch();
    }

    public static function getPermissionsByUserId($user_id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT DISTINCT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id in
                            (SELECT role_id
                                    FROM user_role
                                        WHERE user_id = ?)'
        );
        $stmt->execute([$user_id]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }
}
