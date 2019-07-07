<?php

class MailTemplate
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

    public static function new()
    {
        // $type = Request::post('type', true);
        $type = 'member';
        $subject = Request::post('subject', true);
        $body = Request::post('body', false); //TODO: Possible issue!!! Needed for HTML markup in templates
        $status = 1;
        $return = self::writenew($type, $subject, $body, $status);
        if ($return === false) {
            Session::add('feedback_negative', "Nieuwe template aanmaken mislukt.");
        } else {
            Session::add('feedback_positive', "Template toegevoegd (ID = ".$return.')');
            Redirect::to("mail/templates/");
        }
    }

    public static function edit()
    {
        // $type = Request::post('type', true);
        $type = 'member';
        $id = Request::post('id', true);

        $subject = Request::post('subject', true);
        $body = Request::post('body', false); //TODO: Possible issue!!! Needed for HTML markup in templates
        $status = 1;
        $return = self::writeedit($id, $type, $subject, $body, $status);
        if ($return === false) {
            Session::add('feedback_negative', "Nieuwe template aanmaken mislukt.");
        } else {
            Session::add('feedback_positive', "Template toegevoegd (ID = ".$return.')');
            Redirect::to("mail/templates/");
        }
    }

    public static function writenew($type, $subject, $body, $status)
    {
        $stmt = DB::conn()->prepare("INSERT INTO mail_templates(id, type, subject, body, status) VALUES (NULL,?,?,?,?)");
        $stmt->execute([$type, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        $id = self::returnLastInsertedId();
        return $id;
    }

    public static function writeedit($id, $type, $subject, $body, $status)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_templates SET id = ?, type = ?, subject = ?, body = ?, status = ?");
        $stmt->execute([$id, $type, $subject, $body, $status]);
        if (!$stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }

    public static function returnLastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_templates");
        $lastId = $stmt->fetchColumn();
        return $lastId;
    }

    public static function replaceholder($placeholder, $placeholdervalue, $body_in)
    {
        $variables = array(
            $placeholder=>$placeholdervalue
        );
        foreach ($variables as $key => $value) {
            $body_out = str_replace('{'.strtoupper($key).'}', $value, $body_in);
        }
        return $body_out;
    }

}