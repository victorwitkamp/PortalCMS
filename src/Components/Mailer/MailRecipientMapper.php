<?php

class MailRecipientMapper
{

    /**
     * Undocumented function
     *
     * @param [type] $email   The e-mailaddress of the recipient
     * @param [type] $mail_id The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param int    $type    Whether the recipient is: 1) recipient, 2) CC, 3) BCC
     * @param [type] $name    The name of the recipient
     *
     * @return void
     */
    public static function create($email, $mail_id, $type = 1, $name = null)
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,?,?)"
        );
        $stmt->execute([$email, $mail_id, $type, $name]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getByMailId($mail_id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_recipients where mail_id = ?");
        $stmt->execute([$mail_id]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getByMailIdAndType($mail_id, $type)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_recipients where mail_id = ? and type = ?");
        $stmt->execute([$mail_id, $type]);
        if ($stmt->rowCount() === 0) {
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