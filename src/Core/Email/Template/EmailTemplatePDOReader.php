<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

use PDO;
use PortalCMS\Core\Database\DB;

class EmailTemplatePDOReader
{
    public static function get()
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM mail_templates
                    ORDER BY id'
        );
        $stmt->execute([]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getByType(string $type)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM mail_templates
                    WHERE type = ?
                    ORDER BY id'
        );
        $stmt->execute([$type]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById(int $id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM mail_templates
                    WHERE id = ?
                        LIMIT 1'
        );
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getSystemTemplateByName(string $name)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *
                FROM mail_templates
                    WHERE type = 'system'
                        AND name = ?
                            LIMIT 1"
        );
        $stmt->execute([$name]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetch();
    }

    public static function lastInsertedId()
    {
        return DB::conn()->query('SELECT max(id) from mail_templates')->fetchColumn();
    }
}
