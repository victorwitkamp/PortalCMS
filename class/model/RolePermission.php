<?php

class RolePermission
{

    /**
     * Returns the permissions of a role as an array
     *
     * @param string $role_id
     *
     * @return mixed
     */
    public static function getRolePermissions($role_id) {
        $stmt = DB::conn()->prepare(
            "SELECT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id = ?"
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return FALSE;
    }

    /**
     * Check whether a role has a specific permission
     *
     * @param string $role_id
     * @param string $perm_desc
     *
     * @return bool
     */
    public static function isAssigned($role_id, $perm_desc) {
        $stmt = DB::conn()->prepare(
            "SELECT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id = ? and t2.perm_desc = ?"
        );
        $stmt->execute([$role_id, $perm_desc]);
        if ($stmt->rowCount() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public static function assign($role_id, $perm_id) {
        $stmt = DB::conn()->prepare(
            "INSERT INTO role_perm(role_id, perm_id) VALUES (?,?)"
        );
        if ($stmt->execute([$role_id, $perm_id])) {
            return TRUE;
        }
        return FALSE;
    }

    public static function unassign($role_id, $perm_id) {
        $stmt = DB::conn()->prepare(
            "DELETE FROM role_perm
                        where role_id=?
                        AND perm_id=?
                        LIMIT 1"
        );
        if ($stmt->execute([$role_id, $perm_id])) {
            if ($stmt->rowCount() > 0) {
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

    /**
     * Returns the permission ID's for a specific role
     *
     * @param string $role_id
     *
     * @return array
     */
    public static function getPermissionIds($role_id) {
        $stmt = DB::conn()->prepare(
            "SELECT perm_id
                    FROM role_perm
                        WHERE role_id = ?"
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();

        }
        return FALSE;
    }


}