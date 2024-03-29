<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Message\Attachment;

use PDO;
use PortalCMS\Core\Database\Database;

class EmailAttachmentMapper
{
    /**
     * Create a new attachment for an e-mail in the schedule.
     */
    public static function create(int $mailId, string $path, string $name, string $extension, string $encoding = 'base64', string $type = 'application/octet-stream'): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_attachments(id, mail_id, template_id, path, name, extension, encoding, type)
                    VALUES (NULL,?,NULL,?,?,?,?,?)');
        $stmt->execute([ $mailId, $path, $name, $extension, $encoding, $type ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * Create a new attachment for a template.
     */
    public static function createForTemplate(int $templateId, EmailAttachment $attachment): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_attachments(id, mail_id, template_id, path, name, extension, encoding, type)
                    VALUES (NULL,NULL,?,?,?,?,?,?)');
        $stmt->execute([ $templateId, $attachment->path, $attachment->name, $attachment->extension, $attachment->encoding, $attachment->type ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getByMailId(int $mailId): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_attachments where mail_id = ?');
        $stmt->execute([ $mailId ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return null;
    }

    public static function getByTemplateId(int $templateId): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_attachments where template_id = ?');
        $stmt->execute([ $templateId ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function deleteById(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_attachments WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function deleteByMailId(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_attachments WHERE mail_id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }
}
