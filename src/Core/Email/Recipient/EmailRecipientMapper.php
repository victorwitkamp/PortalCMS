<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Recipient;

use PortalCMS\Core\Database\Database;

class EmailRecipientMapper
{
    public static function createRecipient(int $mail_id, string $emailAddress, string $name = null): bool
    {
        $stmt = Database::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,1,?)'
        );
        $stmt->execute([$emailAddress, $mail_id, $name]);
        return ($stmt->rowCount() === 1);
    }

    public static function createCC(int $mail_id, string $emailAddress, string $name = null): bool
    {
        $stmt = Database::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,2,?)'
        );
        $stmt->execute([$emailAddress, $mail_id, $name]);
        return ($stmt->rowCount() === 1);
    }

    public static function createBCC(int $mail_id, string $emailAddress, string $name = null): bool
    {
        $stmt = Database::conn()->prepare(
            'INSERT INTO mail_recipients(id, email, mail_id, type, name) VALUES (NULL,?,?,3,?)'
        );
        $stmt->execute([$emailAddress, $mail_id, $name]);
        return ($stmt->rowCount() === 1);
    }

    public function getAll(int $mail_id) : ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ?
        ');
        $stmt->execute([$mail_id]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll();
    }

    public function getRecipients(int $mailId) : ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 1
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll();
    }

    public static function getCC(int $mailId) : ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 2
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll();
    }

    public static function getBCC(int $mailId) : ?array
    {
        $stmt = Database::conn()->prepare('
            SELECT * FROM mail_recipients where mail_id = ? and type = 3
        ');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll();
    }
}
