<?php

class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }

    /**
     * Create a new role
     *
     * @param string $role_name
     *
     * @return bool
     */
    public static function new($role_name) {
        $stmt = DB::conn()->prepare(
            "INSERT INTO roles
                        (role_name)
                        VALUES (?)"
        );
        if ($stmt->execute([$role_name])) {
            return true;
        }
        return false;
    }

    /**
     * Delete an existing role
     *
     * @param string $role_id
     *
     * @return bool
     */
    public static function delete($role_id) {
        $stmt = DB::conn()->prepare(
            "DELETE FROM roles
                        where role_id=?
                        LIMIT 1"
        );
        if ($stmt->execute([$role_id])) {
            return true;
        }
        return false;
    }

    /**
     * Assign a role to a user
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function assign($user_id, $role_id) {
        if (self::isRoleAssigned($user_id, $role_id)) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Rol is reeds toegewezen aan deze gebruiker.");
            return false;
        }
        $stmt = DB::conn()->prepare(
            "INSERT INTO user_role
                    (user_id, role_id)
                    VALUES (?,?)"
        );
        if ($stmt->execute([$user_id, $role_id])) {
            $_SESSION['response'][] = array("status"=>"success", "message"=>"Rol toegewezen.");
            return true;
        }
        $_SESSION['response'][] = array("status"=>"error", "message"=>"Fout bij toewijzen van rol.");
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
    public static function unassign($user_id, $role_id) {
        if (!self::isRoleAssigned($user_id, $role_id)) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Rol is niet aan deze gebruiker toegewezen. Er is geen toewijzing om te verwijderen.");
            return false;
        }
        $stmt = DB::conn()->prepare(
            "DELETE FROM user_role
                    where user_id=?
                        and role_id=?"
        );
        if ($stmt->execute([$user_id, $role_id])) {
            return true;
        }
        return false;
    }

    /**
     * Check whether a user has a specific role
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function isRoleAssigned($user_id, $role_id) {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM user_role
                        WHERE user_id = ?
                            and role_id = ?
                                limit 1"
        );
        $stmt->execute([$user_id, $role_id]);
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Returns a role as an array
     *
     * @param string $role_id
     *
     * @return mixed
     */
    public static function get($role_id) {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM roles
                        WHERE role_id = ?
                            limit 1"
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch();
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
    public static function hasPerm($role_id, $perm_desc) {
        $stmt = DB::conn()->prepare(
            "SELECT t2.perm_desc
                    FROM role_perm as t1
                        JOIN permissions as t2 ON t1.perm_id = t2.perm_id
                            WHERE t1.role_id = ? and t2.perm_desc = ?"
        );
        $stmt->execute([$role_id, $perm_desc]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Returns the permission ID's for a specific role
     *
     * @param string $role_id
     *
     * @return array
     */
    public static function getRolePermissionIds($role_id) {
        $stmt = DB::conn()->prepare(
            "SELECT perm_id
                    FROM role_perm
                        WHERE role_id = ?"
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();

        }
        return false;
    }

}