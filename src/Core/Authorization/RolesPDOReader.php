<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Authorization;

use PDO;
use PortalCMS\Core\Database\DB;

class RolesPDOReader
{
    public static function getRoles() : ?array
    {
        $stmt = DB::conn()->query('SELECT * FROM roles ORDER BY role_id ');
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
}
