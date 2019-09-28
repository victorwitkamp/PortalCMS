<?php

class MailRecipientMapper
{

    public static function create($recipient, $mail_id = NULL)
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_recipients(id, recipient, mail_id) VALUES (NULL,?,?)"
        );
        $stmt->execute([$recipient, $mail_id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getByMailId($mail_id)
    {

        $stmt = DB::conn()->prepare("SELECT * FROM mail_recipients where mail_id = ?");
        $stmt->execute([$mail_id]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function CountByMailId($mail_id)
    {

        $stmt = DB::conn()->prepare("SELECT COUNT(*) FROM mail_recipients where mail_id = ?");
        $stmt->execute([$mail_id]);
        return $stmt->rowCount();
    }
}