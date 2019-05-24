<?php

class MailSchedule
{
    public static function doesMailIdExist($mailId)
    {
        $stmt = DB::conn()->prepare("SELECT id FROM mail_schedule WHERE id = ? limit 1");
        $stmt->execute([$mailId]);
        if ($stmt->rowCount() == 0) {
            return false;
        }
        return true;
    }
    public static function get()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule ORDER BY id");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public static function count()
    {
        $count = DB::conn()->query("SELECT count(1) FROM mail_schedule")->fetchColumn();
        return $count;
    }

    public static function getScheduledMailById($id)
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_schedule WHERE id = ? limit 1");
        $stmt->execute([$id]);
        if (!$stmt->rowCount() == 1) {
            return false;
        } else {
            return $stmt->fetch();
        }
    }

    public static function sendbyid()
    {
        if (!empty($_POST['id'])) {
            foreach ($_POST['id'] as $id) {
                $row = self::getScheduledMailById($id);
                if ($row['status'] !== '1') {
                    $_SESSION['response'][] = array("status"=>"error", "message"=>"Reeds verstuurd");
                    Redirect::redirectPage("mailscheduler/");
                    return false;
                } else {
                    $senderemail = $row['sender_email'];
                    $recipientEmail = $row['recipient_email'];
                    $title = $row['subject'];
                    $body = $row['body'];
                    if (!empty($recipientEmail) && !empty($title) && !empty($body)) {
                        if (MailController::sendMail($senderemail, $recipientEmail, $title, $body)) {
                            self::updateStatusById($id, '2');
                            self::setDateSent($id);
                        } else {
                            self::updateStatusById($id, '3'); // e
                            self::setErrorMessageById($id, MailController::$error);
                        }
                    } else {
                        $_SESSION['response'][] = array("status"=>"error", "message"=>"Mail incompleet");
                    }
                }
            }
        }
        Redirect::redirectPage("mailscheduler/");

    }

    public static function newWithTemplate()
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $type = Request::post('type', true);
        $templateid = Request::post('templateid', true);
        $template = MailTemplates::getTemplateById($templateid);
        $templatebody = $template['body'];
        $count_created = 0;
        $count_failed = 0;
        if ($type === 'member') {

            if (!empty($_POST['recipients'])) {
                foreach ($_POST['recipients'] as $value) {

                    $member = Member::getMemberById($value);
                    $mailBody = self::replaceholdersMember($value, $templatebody);
                    $return = self::writenew($sender_email, $member['emailadres'], $value, $template['subject'], $mailBody);
                    if ($return === false) {
                        $count_failed += 1;
                    } else {
                        $count_created += 1;
                    }
                }
                if ($count_failed === 0) {
                    $_SESSION['response'][] = array("status"=>"success", "message"=>"Totaal aantal berichten aangemaakt:".$count_created);
                    Redirect::redirectPage("mailscheduler/");
                } else {
                    $_SESSION['response'][] = array("status"=>"error", "message"=>"Nieuwe email aanmaken mislukt.");
                }
            }
        }
    }

    public static function replaceholdersMember($memberid, $templatebody)
    {
        $member = Member::getMemberById($memberid);
        $afzender = SiteSettings::getStaticSiteSetting('site_name');
        $variables = array(
            "voornaam"=>$member['voornaam'],
            "achternaam"=>$member['achternaam'],
            "iban"=>$member['iban'],
            "afzender"=>$afzender
        );
        foreach ($variables as $key => $value) {
            $templatebody = str_replace('{'.strtoupper($key).'}', $value, $templatebody);

        }
        return $templatebody;


    }

    public static function new()
    {
        $sender_email = Config::get('EMAIL_SMTP_USERNAME');
        $recipient_email = Request::post('recipient_email', true);
        $subject = Request::post('subject', true);
        $body = Request::post('body', true);

        $return = self::writenew($sender_email, $recipient_email, $subject, $body);
        if ($return === false) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Nieuwe email aanmaken mislukt.");
        } else {
            $_SESSION['response'][] = array("status"=>"success", "message"=>"Email toegevoegd (ID = ".$return.')');
            Redirect::redirectPage("settings/mailscheduler/");
        }
    }

    public static function setErrorMessageById($id, $message) {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET errormessage =? where id=?");
        $stmt->execute([$message, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateStatusById($id, $status)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET status =? where id=?");
        $stmt->execute([$status, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }
    public static function setDateSent($id)
    {
        $stmt = DB::conn()->prepare("UPDATE mail_schedule SET DateSent = CURRENT_TIMESTAMP where id=?");
        $stmt->execute([$id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function writenew($sender_email, $recipient_email, $member_id, $subject, $body, $status = '1')
    {
        $stmt = DB::conn()->prepare("INSERT INTO mail_schedule(id, sender_email, recipient_email, member_id, subject, body, status) VALUES (NULL,?,?,?,?,?,?)");
        $stmt->execute([$sender_email, $recipient_email, $member_id, $subject, $body, $status]);
        if (!$stmt) {
            return false;
        }
        $id = self::returnLastInsertedId();
        return $id;
    }

    public static function returnLastInsertedId() {
        $stmt = DB::conn()->query("SELECT max(id) from mail_schedule");
        $lastId = $stmt->fetchColumn();
        return $lastId;
    }

}