<?php

class Permission
{
    public static function get($perm_id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM permissions
                        WHERE perm_id = ?"
        );
        $stmt->execute([$perm_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function getUserPermissions($user_id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT DISTINCT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id in
                            (SELECT role_id
                                    FROM user_role
                                        WHERE user_id = ?)"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }


}