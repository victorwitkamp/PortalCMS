<?php

use PortalCMS\Core\DB;

class MailAttachmentMapper
{
    public static function create($mail_id, $path, $name, $extension, $encoding = 'base64', $type = 'application/octet-stream')
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_attachments(id, mail_id, path, name, extension, encoding, type) VALUES (NULL,?,?,?,?,?,?)"
        );
        $stmt->execute([$mail_id, $path, $name, $extension, $encoding, $type]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function createForTemplate($template_id, $path, $name, $extension, $encoding = 'base64', $type = 'application/octet-stream')
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_attachments(id, template_id, path, name, extension, encoding, type) VALUES (NULL,?,?,?,?,?,?)"
        );
        $stmt->execute([$template_id, $path, $name, $extension, $encoding, $type]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function getByMailId($mailId)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_attachments where mail_id = ?");
        $stmt->execute([$mailId]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getByTemplateId($templateId)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_attachments where template_id = ?");
        $stmt->execute([$templateId]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function lastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_attachments");
        return $stmt->fetchColumn();
    }

    public static function deleteById($id)
    {
        $stmt = DB::conn()->prepare("DELETE FROM mail_attachments WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1 ? true : false);
    }

    public static function deleteByMailId($id)
    {
        $stmt = DB::conn()->prepare("DELETE FROM mail_attachments WHERE mail_id = ? LIMIT 1");
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1 ? true : false);
    }
}
