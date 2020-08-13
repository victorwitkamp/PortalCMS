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
    /**
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public static function getStatusById(int $id)
    {
        $stmt = Database::conn()->prepare('SELECT status FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return $stmt->fetchColumn();
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * @return array
     */
    public static function getHistory(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * @param int $batch_id
     * @return array
     */
    public static function getByBatchId(int $batch_id): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule where batch_id = ? ORDER BY id ');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchAll();
    }

    /**
     * @param int $batch_id
     * @return array
     */
    public static function getScheduledIdsByBatchId(int $batch_id): array
    {
        $stmt = Database::conn()->prepare('SELECT id FROM mail_schedule WHERE status = 1 and batch_id = ? ORDER BY id ');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchAll();
    }

    /**
     * @param int $id
     * @return object|null
     */
    public static function getById(int $id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * @param int $batch_id
     * @return int
     */
    public static function deleteByBatchId(int $batch_id): int
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_schedule WHERE batch_id = ?');
        $stmt->execute([ $batch_id ]);
        return $stmt->rowCount();
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int|null    $batchId
     * @param int|null    $memberId
     * @param string|null $subject
     * @param string|null $body
     * @param int         $status
     * @return bool
     */
    /**
     * @param int|null    $batchId
     * @param int|null    $memberId
     * @param string|null $subject
     * @param string|null $body
     * @param int         $status
     * @return bool
     */
    /**
     * @param int|null    $batchId
     * @param int|null    $memberId
     * @param string|null $subject
     * @param string|null $body
     * @param int         $status
     * @return bool
     */
    public static function create(int $batchId = null, int $memberId = null, string $subject = null, string $body = null, int $status = 1): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_schedule(
                          id, batch_id, sender_email, member_id, subject, body, status
                          ) VALUES (
                            NULL,?,NULL,?,?,?,?
                            )');
        $stmt->execute([ $batchId, $memberId, $subject, $body, $status ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    /**
     * @return mixed
     */
    /**
     * @return mixed
     */
    public static function lastInsertedId()
    {
        return Database::conn()->query('SELECT max(id) from mail_schedule')->fetchColumn();
    }

    /**
     * @param int $id
     * @param int $status
     * @return bool
     */
    /**
     * @param int $id
     * @param int $status
     * @return bool
     */
    /**
     * @param int $id
     * @param int $status
     * @return bool
     */
    public static function updateStatus(int $id, int $status): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET status =? where id=?');
        $stmt->execute([ $status, $id ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param int    $id
     * @param string $senderName
     * @param string $senderEmail
     * @return bool
     */
    /**
     * @param int    $id
     * @param string $senderName
     * @param string $senderEmail
     * @return bool
     */
    /**
     * @param int    $id
     * @param string $senderName
     * @param string $senderEmail
     * @return bool
     */
    public static function updateSender(int $id, string $senderName, string $senderEmail): bool
    {
        $sender = $senderName . ' (' . $senderEmail . ')';
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET sender_email =? where id=?');
        $stmt->execute([ $sender, $id ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    public static function updateDateSent(int $id): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id=?');
        $stmt->execute([ $id ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param int    $id
     * @param string $message
     * @return bool
     */
    /**
     * @param int    $id
     * @param string $message
     * @return bool
     */
    /**
     * @param int    $id
     * @param string $message
     * @return bool
     */
    public static function setErrorMessageById(int $id, string $message): bool
    {
        $stmt = Database::conn()->prepare('UPDATE mail_schedule SET errormessage =? where id=?');
        $stmt->execute([ $message, $id ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }
}
