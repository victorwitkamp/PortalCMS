<?php 

class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }

    public static function new($role_name) {
        $stmt = DB::conn()->prepare("INSERT INTO roles(role_name) VALUES (?)");
        if (!$stmt->execute([$role_name])) {
            return false;
        }
        return true;
    }
    public static function delete($role_id) {
        $stmt = DB::conn()->prepare("DELETE FROM roles where role_id=? LIMIT 1");
        if (!$stmt->execute([$role_id])) {
            return false;
        }            
        return true;
    }

    public static function assign($user_id, $role_id) {
        if (!self::isRoleAssigned($user_id, $role_id)) {
            $stmt = DB::conn()->prepare("INSERT INTO user_role (user_id, role_id) VALUES (?,?)");
            if ($stmt->execute([$user_id, $role_id])) {
                return true;
            }
            return false;
        }
        $_SESSION['response'][] = array("status"=>"error","message"=>"Rol is reeds toegewezen.<br>" );
        return false;
    }

    public static function unassign($user_id, $role_id) {
        if (self::isRoleAssigned($user_id, $role_id)) {
            $stmt = DB::conn()->prepare("DELETE FROM user_role where user_id=? and role_id=?");
            if ($stmt->execute([$user_id, $role_id])) {
                return true;
            }
            return false;
        }
        $_SESSION['response'][] = array("status"=>"error","message"=>"Rol is reeds toegewezen.<br>" );
        return false;
    }

    public static function isRoleAssigned($user_id, $role_id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM user_role WHERE user_id = ? and role_id = ? limit 1");
        $stmt->execute([$user_id, $role_id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function get($role_id) {
        $stmt = DB::conn()->prepare("SELECT * FROM roles WHERE role_id = ? limit 1");
        $stmt->execute([$role_id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function getRolePermissions($role_id) {
        $permissions = array();
        $stmt = DB::conn()->prepare(
            "SELECT t2.perm_desc 
            FROM role_perm as t1
            JOIN permissions as t2 ON t1.perm_id = t2.perm_id
            WHERE t1.role_id = ?"
        );
        $stmt->execute([$role_id]);
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $permissions[$row["perm_desc"]] = true;
        // }
        // return $permissions;
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }

    public static function hasPerm($role_id, $perm_desc) {
        $stmt = DB::conn()->prepare(
            "SELECT t2.perm_desc 
            FROM role_perm as t1
            JOIN permissions as t2 ON t1.perm_id = t2.perm_id
            WHERE t1.role_id = ? and t2.perm_desc = ?"
        );
        $stmt->execute([$role_id, $perm_desc]);
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function getRolePermissionIds($role_id) {
        $permissionids = array();
        $stmt = DB::conn()->prepare("SELECT perm_id FROM role_perm WHERE role_id = ?");
        $stmt->execute([$role_id]);
        if (!$stmt->rowCount() > 0) {
            return false;
        } else {
            $rows = $stmt->fetchAll();
            return $rows;
        }
    }
}