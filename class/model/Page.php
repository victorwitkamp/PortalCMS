<?php

/**
 * Class : Page (Page.class.php)
 * Details : Page Class.
*/

class Page 
{
    public static function checkPage($page_id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM pages WHERE id = ? limit 1');
        $stmt->execute([$page_id]);
        if (!$stmt->rowCount() > 0) {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Pagina bestaat niet.");
            return false;
        } else {
            return true;
        }
    }

    public static function getPage($page_id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM pages WHERE id = ? limit 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $row;
            }
        } else {
            $_SESSION['response'][] = array("status"=>"warning", "message"=>"Geen pagina gevonden voor weergave.");
            return false;
        }
    }

    public static function updatePage($page_id, $content)
    {
        $stmt = DB::conn()->prepare('SELECT id FROM pages WHERE id = ? limit 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() > 0) {
            $stmt = DB::conn()->prepare("UPDATE pages SET content=? WHERE id=?");
            if (!$stmt->execute([$content, $page_id])) {
                $_SESSION['response'][] = array("status"=>"error", "message"=>"Wijzigen van evenement mislukt."); 
                return false;
            } else {
                $_SESSION['response'][] = array("status"=>"success", "message"=>"Pagina opgeslagen.");
                UserActivity::registerUserActivity('updatePage');
                return true;
            }
        } else {
            $_SESSION['response'][] = array("status"=>"error", "message"=>"Wijzigen van evenement mislukt. Evenement bestaat niet."); 
            return false;
        }
    }

}