<?php

class MailTemplates
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
        $stmt = DB::conn()->prepare("SELECT * FROM mail_templates WHERE id = ? limit 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function new()
    {
        $type = Request::post('type', true);
        $subject = Request::post('subject', true);
        $body = Request::post('body', true);
        $status = 1;
        $return = self::writenew($type, $subject, $body, $status);
        if ($return === false) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Nieuwe template aanmaken mislukt.");
        } else {
            $_SESSION['response'][] = array("status"=>"success", "message"=>"Template toegevoegd (ID = ".$return.')');
            Redirect::redirectPage("mailscheduler/templates/");
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

    public static function returnLastInsertedId() {
        $stmt = DB::conn()->query("SELECT max(id) from mail_templates");
        $lastId = $stmt->fetchColumn();
        return $lastId;
    }

    // alle function voor de bestaande mailtext settings

    // function savesitesettings() {
    //     self::setMailText(Request::post('MailText_ResetPassword'), 'ResetPassword');
    //     self::setMailText(Request::post('MailText_Signup'), 'Signup');
    // }


    function getMailText($name)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_text WHERE name = ?");
        $stmt->execute([$name]);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $row['text'];
        }
    }

    public static function getStaticMailText($name)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_text WHERE name = ?");
        $stmt->execute([$name]);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $row['text'];
        }
    }


    function setMailText($text, $name)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_text SET text = ? WHERE name = ?");
        if (!$stmt->execute([$text, $name])) {
            return false;
        } else {
            return true;
        }
    }

}