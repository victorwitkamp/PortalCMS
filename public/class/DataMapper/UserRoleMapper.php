<?php

class UserRoleMapper
{
    static function getByUserId($Id)
    {
        $stmt = DB::conn()->prepare("SELECT role_id FROM user_role where user_id = ? ORDER BY role_id");
        $stmt->execute([$Id]);
        return $stmt->fetchAll();
    }
}
