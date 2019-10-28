<?php

namespace PortalCMS\Core\Email\Recipient;

use PortalCMS\Core\Database\DB;

class EmailRecipientMapper
{
    /**
     * Undocumented function
     *
     * @param string $email   The e-mailaddress of the recipient
     * @param int    $mail_id The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param string $name    The name of the recipient
     *
     * @return boolean
     */
    public static function createRecipient($mail_id, $email, $name = null)
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,1,?)'
        );
        $stmt->execute([$email, $mail_id, $name]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * Undocumented function
     *
     * @param string $email   The e-mailaddress of the recipient
     * @param int    $mail_id The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param string $name    The name of the recipient
     *
     * @return boolean
     */
    public static function createCC($mail_id, $email, $name = null)
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,2,?)'
        );
        $stmt->execute([$email, $mail_id, $name]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * Undocumented function
     *
     * @param string $email   The e-mailaddress of the recipient
     * @param int    $mail_id The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param string $name    The name of the recipient
     *
     * @return boolean
     */
    public static function createBCC($mail_id, $email, $name = null)
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,3,?)'
        );
        $stmt->execute([$email, $mail_id, $name]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * getAll
     *
     * @param int $mail_id The ID of the e-mail that was scheduled in the MailSchedule table.
     *
     * @return array|bool
     */
    public static function getAll($mail_id)
    {
        $stmt = DB::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ?
        ');
        $stmt->execute([$mail_id]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * getRecipients
     *
     * @param int    $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param int    $type    Whether the recipient is: 1) recipient, 2) CC, 3) BCC
     *
     * @return array|bool
     */
    public static function getRecipients($mailId)
    {
        $stmt = DB::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 1
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * getCC
     *
     * @param int    $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param int    $type    Whether the recipient is: 1) recipient, 2) CC, 3) BCC
     *
     * @return array|bool
     */
    public static function getCC($mailId)
    {
        $stmt = DB::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 2
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * getBCC
     *
     * @param int    $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     * @param int    $type    Whether the recipient is: 1) recipient, 2) CC, 3) BCC
     *
     * @return array|bool
     */
    public static function getBCC($mailId)
    {
        $stmt = DB::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 3
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }
}
