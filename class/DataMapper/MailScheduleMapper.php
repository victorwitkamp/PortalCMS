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

    public static function get()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function count()
    {
        return DB::conn()->query("SELECT count(1) FROM mail_schedule")->fetchColumn();
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

    public static function create($sender_email, $recipient_email, $member_id, $subject, $body, $status = '1')
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_schedule(id, sender_email, recipient_email, member_id, subject, body, status) VALUES (NULL,?,?,?,?,?,?)"
        );
        $stmt->execute([$sender_email, $recipient_email, $member_id, $subject, $body, $status]);
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