<?php

class MailTemplateMapper
{
    public static function getTemplates()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_templates ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();

    }

    public static function getTemplatesByType($type)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_templates WHERE type = ? ORDER BY id");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public static function getTemplateById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_templates WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function getSystemTemplateByName($name)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_templates WHERE type = 'system' AND name = ? LIMIT 1");
        $stmt->execute([$name]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function create($type, $subject, $body, $status)
    {
        $stmt = DB::conn()->prepare("INSERT INTO mail_templates(id, type, subject, body, status) VALUES (NULL,?,?,?,?)");
        $stmt->execute([$type, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        $id = self::lastInsertedId();
        return $id;
    }

    public static function update($id, $type, $subject, $body, $status)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_templates SET type = ?, subject = ?, body = ?, status = ? WHERE id = ?");
        $stmt->execute([$type, $subject, $body, $status, $id]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }

    public static function lastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_templates");
        $lastId = $stmt->fetchColumn();
        return $lastId;
    }

}