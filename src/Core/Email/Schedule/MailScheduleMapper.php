<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Schedule;

use PDO;
use PortalCMS\Core\Database\DB;

class MailScheduleMapper
{
    public static function exists(int $id): bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    public static function getStatusById(int $id)
    {
        $stmt = DB::conn()->prepare('SELECT status FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public static function getAll(): array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getHistory(): array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getByBatchId(int $batch_id): array
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule where batch_id = ? ORDER BY id ');
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll();
    }

    public static function getScheduledIdsByBatchId(int $batch_id): array
    {
        $stmt = DB::conn()->prepare('SELECT id FROM mail_schedule WHERE status = 1 and batch_id = ? ORDER BY id ');
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll();
    }

    public static function getById(int $id) : ?object
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function deleteByBatchId(int $batch_id): int
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_schedule WHERE batch_id = ?');
        $stmt->execute([$batch_id]);
        return $stmt->rowCount();
    }

    public static function deleteById(int $id): bool
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    public static function create(int $batchId = null, int $memberId = null, string $subject = null, string $body = null, int $status = 1): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_schedule(
                          id, batch_id, sender_email, member_id, subject, body, status
                          ) VALUES (
                            NULL,?,NULL,?,?,?,?
                            )'
        );
        $stmt->execute([$batchId, $memberId, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function lastInsertedId()
    {
        return DB::conn()->query('SELECT max(id) from mail_schedule')->fetchColumn();
    }

    public static function updateStatus(int $id, int $status): bool
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET status =? where id=?');
        $stmt->execute([$status, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateSender(int $id, string $senderName, string $senderEmail): bool
    {
        $sender = $senderName . ' (' . $senderEmail . ')';
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET sender_email =? where id=?');
        $stmt->execute([$sender, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateDateSent(int $id): bool
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id=?');
        $stmt->execute([$id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function setErrorMessageById(int $id, string $message): bool
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET errormessage =? where id=?');
        $stmt->execute([$message, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }
}
