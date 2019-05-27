<?php

class Permission
{
    public static function get($perm_id) {
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

    public static function assign($role_id, $perm_id) {
        if (self::assignAction($role_id, $perm_id)) {
            Session::add('feedback_positive', "Permissie toegewezen.");
            return true;
        }
        return false;
    }

    public static function assignAction($role_id, $perm_id) {
        $stmt = DB::conn()->prepare(
            "INSERT INTO role_perm(role_id, perm_id)
                        VALUES (?,?)"
        );
        if ($stmt->execute([$role_id, $perm_id])) {
            return true;
        }
        return false;
    }
    public static function unassign($role_id, $perm_id) {
        if (self::unassignAction($role_id, $perm_id)) {
            Session::add('feedback_positive', "Permissie verwijderd.");
            return true;
        }
        return false;
    }

    public static function unassignAction($role_id, $perm_id) {
        $stmt = DB::conn()->prepare(
            "DELETE FROM role_perm
                        where role_id=?
                        AND perm_id=?
                        LIMIT 1"
        );
        if ($stmt->execute([$role_id, $perm_id])) {
            return true;
        }
        return false;
    }

    public static function getPermissions($user_id) {
        $stmt = DB::conn()->prepare(
            "SELECT DISTINCT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id in (
                                SELECT role_id from user_role where user_id = ?
                                )"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public static function hasPrivilege($perm) {
        $Roles = User::getRoles(Session::get('user_id'));
        foreach ($Roles as $Role) {
            if (Role::hasPerm($Role['role_id'], $perm)) {
                return true;
            }
        }
        return false;
    }
}