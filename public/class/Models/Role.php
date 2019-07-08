<?php

class Role
{
    protected $permissions;

    protected function __construct()
    {
        $this->permissions = array();
    }

    /**
     * Returns a role as an array
     *
     * @param string $role_id
     *
     * @return mixed
     */
    public static function get($role_id)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                    FROM roles
                        WHERE role_id = ?
                            LIMIT 1"
        );
        $stmt->execute([$role_id]);
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch();
        }
        return false;
    }

    /**
     * Create a new role
     *
     * @param string $role_name
     *
     * @return bool
     */
    public static function create($role_name)
    {
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
    public static function delete($role_id)
    {
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







}
