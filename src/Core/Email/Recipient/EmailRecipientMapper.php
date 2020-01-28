<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

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
     * @return bool
     */
    public static function createRecipient(int $mail_id, string $email, string $name = null): bool
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
     * @return bool
     */
    public static function createCC(int $mail_id, string $email, string $name = null): bool
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
     * @return bool
     */
    public static function createBCC(int $mail_id, string $email, string $name = null): bool
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
    public function getAll(int $mail_id)
    {
        $stmt = DB::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ?
        ');
        $stmt->execute([$mail_id]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll();
    }

    /**
     * getRecipients
     *
     * @param int    $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     *
     * @return array|bool
     */
    public function getRecipients(int $mailId)
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
     * @param int $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     * @return array|bool
     */
    public static function getCC(int $mailId)
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
     * @param int $mailId The ID of the e-mail that was scheduled in the MailSchedule table.
     * @return array|bool
     */
    public static function getBCC(int $mailId)
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
