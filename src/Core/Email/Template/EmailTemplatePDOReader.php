<?php

namespace PortalCMS\Core\Email\Template;

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

    public static function getByType($type)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM mail_templates
                    WHERE type = ?
                    ORDER BY id'
        );
        $stmt->execute([$type]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $stmt = DB::conn()->prepare(
            'SELECT *
                FROM mail_templates
                    WHERE id = ?
                        LIMIT 1'
        );
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function getSystemTemplateByName($name)
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
