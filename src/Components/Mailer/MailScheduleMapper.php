<?php

class MailScheduleMapper
{
    public static function exists($id)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM mail_schedule WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        }
        return true;
    }

    public static function getScheduled()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE status = 1 ORDER BY id ASC");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getScheduledByBatchId($batch_id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE status = 1 and batch_id = ? ORDER BY id ASC");
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll();
    }

    public static function getHistory()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE status > 1 ORDER BY id ASC");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        }
        return $stmt->fetch();
    }

    public static function deleteById($id)
    {
        $stmt = DB::conn()->prepare("DELETE FROM mail_schedule WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        }
        return true;
    }

    public static function create($batch_id, $sender_email, $member_id, $subject, $body, $status = '1')
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_schedule(id, batch_id, sender_email, member_id, subject, body, status) VALUES (NULL,?,?,?,?,?,?)"
        );
        $stmt->execute([$batch_id, $sender_email, $member_id, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function lastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_schedule");
        return $stmt->fetchColumn();
    }

    public static function updateStatus($id, $status)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET status =? where id=?");
        $stmt->execute([$status, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateDateSent($id)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id=?");
        $stmt->execute([$id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function setErrorMessageById($id, $message)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET errormessage =? where id=?");
        $stmt->execute([$message, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }
}
