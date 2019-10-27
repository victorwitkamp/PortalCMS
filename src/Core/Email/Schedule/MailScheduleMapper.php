<?php

namespace PortalCMS\Core\Email\Schedule;

use PortalCMS\Core\Database\DB;

class MailScheduleMapper
{
    public static function exists($id)
    {
        $stmt = DB::conn()->prepare('SELECT id FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1 ? true : false);
    }

    public static function getStatusById($mailId)
    {
        $stmt = DB::conn()->prepare('SELECT status FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$mailId]);
        return $stmt->fetchColumn();
    }

    public static function getAll()
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule ORDER BY id ASC');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getHistory()
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ASC');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getByBatchId($batch_id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule where batch_id = ? ORDER BY id ASC');
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll();
    }

    public static function getScheduledIdsByBatchId($batch_id)
    {
        $stmt = DB::conn()->prepare('SELECT id FROM mail_schedule WHERE status = 1 and batch_id = ? ORDER BY id ASC');
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function deleteByBatchId($batch_id)
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_schedule WHERE batch_id = ?');
        $stmt->execute([$batch_id]);
        return $stmt->rowCount();
    }

    public static function deleteById($id)
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_schedule WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1 ? true : false);
    }

    public static function create($batch_id, $member_id, $subject, $body, $status = '1')
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_schedule(id, batch_id, sender_email, member_id, subject, body, status) VALUES (NULL,?,NULL,?,?,?,?)'
        );
        $stmt->execute([$batch_id, $member_id, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function lastInsertedId()
    {
        return DB::conn()->query('SELECT max(id) from mail_schedule')->fetchColumn();
    }

    public static function updateStatus($id, $status)
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET status =? where id=?');
        $stmt->execute([$status, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateSender($id, $senderName, $senderEmail)
    {
        $sender = $senderName . ' (' . $senderEmail . ')';
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET sender_email =? where id=?');
        $stmt->execute([$sender, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateDateSent($id)
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id=?');
        $stmt->execute([$id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function setErrorMessageById($id, $message)
    {
        $stmt = DB::conn()->prepare('UPDATE mail_schedule SET errormessage =? where id=?');
        $stmt->execute([$message, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }
}
