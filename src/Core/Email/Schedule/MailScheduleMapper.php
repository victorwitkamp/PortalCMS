<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Schedule;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class MailScheduleMapper
 * @package PortalCMS\Core\Email\Schedule
 */
class MailScheduleMapper
{
    public static function exists(int $id): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function getStatusById(int $id)
    {
        $stmt = Database::conn()->prepare('SELECT status FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return $stmt->fetchColumn();
    }

    public static function getAll(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getHistory(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getByBatchId(int $batch_id): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule where batch_id = ? ORDER BY id ');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchAll();
    }

    public static function getScheduledIdsByBatchId(int $batch_id): array
    {
        $stmt = Database::conn()->prepare('SELECT id FROM mail_schedule WHERE status = 1 and batch_id = ? ORDER BY id ');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public static function deleteByBatchId(int $batch_id): int
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_schedule WHERE batch_id = ?');
        $stmt->execute([ $batch_id ]);
        return $stmt->rowCount();
    }

    public static function deleteById(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function create(int $batchId = null, int $memberId = null, string $subject = null, string $body = null, int $status = 1): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_schedule(
            id, batch_id, sender_email, member_id, subject, body, status
        ) VALUES (
            NULL,?,NULL,?,?,?,?
        )');
        return $stmt->execute([ $batchId, $memberId, $subject, $body, $status ]);
    }

    public static function lastInsertedId()
    {
        return Database::conn()->query('SELECT max(id) from mail_schedule')->fetchColumn();
    }

    public static function updateStatus(int $id, int $status = null): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET status = ? where id = ?');
        $stmt->execute([ $status, $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateSender(int $id, string $senderName, string $senderEmail): bool
    {
        $sender = $senderName . ' (' . $senderEmail . ')';
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET sender_email = ? where id = ?');
        $stmt->execute([ $sender, $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateDateSent(int $id): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id = ?');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    public static function setErrorMessageById(int $id, string $message = null): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET errormessage = ? where id = ?');
        $stmt->execute([ $message, $id ]);
        return ($stmt->rowCount() === 1);
    }
}
