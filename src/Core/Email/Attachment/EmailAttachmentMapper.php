<?php

namespace PortalCMS\Core\Email\Attachment;

use PortalCMS\Core\Database\DB;

class EmailAttachmentMapper
{
    /**
     * Create a new attachment for an e-mail in the schedule.
     *
     * @param int $mailId
     * @param string $path
     * @param string $name
     * @param string $extension
     * @param string $encoding
     * @param string $type
     *
     * @return bool
     */
    public static function create(int $mailId, string $path, string $name, string $extension, string $encoding = 'base64', string $type = 'application/octet-stream')
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_attachments(id, mail_id, template_id, path, name, extension, encoding, type)
                    VALUES (NULL,?,NULL,?,?,?,?,?)'
        );
        $stmt->execute([$mailId, $path, $name, $extension, $encoding, $type]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * Create a new attachment for a template.
     *
     * @param int $templateId
     * @param string $path
     * @param string $name
     * @param string $extension
     * @param string $encoding
     * @param string $type
     *
     * @return bool
     */
    public static function createForTemplate(int $templateId, string $path, string $name, string $extension, string $encoding = 'base64', string $type = 'application/octet-stream')
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_attachments(id, mail_id, template_id, path, name, extension, encoding, type)
                    VALUES (NULL,NULL,?,?,?,?,?,?)'
        );
        $stmt->execute([$templateId, $path, $name, $extension, $encoding, $type]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param $mailId
     * @return array|bool
     */
    public static function getByMailId($mailId)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_attachments where mail_id = ?');
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * @param $templateId
     * @return array|bool
     */
    public static function getByTemplateId($templateId)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_attachments where template_id = ?');
        $stmt->execute([$templateId]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * @return mixed
     */
    public static function lastInsertedId()
    {
        return DB::conn()->query('SELECT max(id) from mail_attachments')->fetchColumn();
    }

    /**
     * @param $id
     * @return bool
     */
    public static function deleteById($id)
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_attachments WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function deleteByMailId($id)
    {
        $stmt = DB::conn()->prepare('DELETE FROM mail_attachments WHERE mail_id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }
}
