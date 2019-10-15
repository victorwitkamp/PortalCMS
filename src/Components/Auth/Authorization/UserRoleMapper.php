<?php

class UserRoleMapper
{
    public static function getByUserId($Id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT role_id
                FROM user_role
                    where user_id = ?
                        ORDER BY role_id"
        );
        $stmt->execute([$Id]);
        return $stmt->fetchAll();
    }


    /**
     * Check whether a user has a specific role
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function isAssigned($user_id, $role_id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM user_role
                        WHERE user_id = ?
                            and role_id = ?
                                LIMIT 1"
        );
        $stmt->execute([$user_id, $role_id]);
        return ($stmt->rowCount() === 1 ? true : false);
    }

    /**
     * Assign a role to a user
     *
     * @param string $user_id
     * @param string $role_id
     *
     * @return bool
     */
    public static function assign($user_id, $role_id)
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO user_role (user_id, role_id)
                    VALUES (?,?)"
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
    public static function unassign($user_id, $role_id)
    {
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
}
