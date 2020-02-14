<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

use PDO;
use PortalCMS\Core\Database\DB;

class EmailTemplateMapper
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

    public static function getByType(string $type): ?array
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

    public static function getById(int $id): object
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

    public function create(EmailTemplate $EmailTemplate) : ?int
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_templates(
                id, type, subject, body, status, CreatedBy
                ) VALUES (
                    NULL,?,?,?,?,?)'
        );
        $stmt->execute([$EmailTemplate->type, $EmailTemplate->subject, $EmailTemplate->body, $EmailTemplate->status, $EmailTemplate->CreatedBy]);
        if (!$stmt) {
            return null;
        }
        return self::lastInsertedId();
    }

    public function update(EmailTemplate $emailTemplate) : bool
    {
        if (empty($emailTemplate->id) || ($emailTemplate->id <= 0)) {
            return false;
        }
        $stmt = DB::conn()->prepare(
            'UPDATE mail_templates
                SET type = ?, subject = ?, body = ?, status = ?
                    WHERE id = ?
                        LIMIT 1'
        );
        $stmt->execute([
            $emailTemplate->type,
            $emailTemplate->subject,
            $emailTemplate->body,
            $emailTemplate->status,
            $emailTemplate->id
        ]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }

    public static function delete(int $id) : bool
    {
        $stmt = DB::conn()->prepare(
            "DELETE FROM mail_templates
                WHERE id = ?
                    AND type != 'system'"
        );
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }
}
