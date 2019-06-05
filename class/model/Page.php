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
            Session::add('feedback_negative', "Pagina bestaat niet.");
            return FALSE;
        } else {
            return TRUE;
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
            Session::add('feedback_negative', "Geen pagina gevonden voor weergave.");
            return FALSE;
        }
    }

    public static function updatePage($page_id, $content)
    {
        $stmt = DB::conn()->prepare('SELECT id FROM pages WHERE id = ? limit 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() > 0) {
            $stmt = DB::conn()->prepare("UPDATE pages SET content=? WHERE id=?");
            if (!$stmt->execute([$content, $page_id])) {
                Session::add('feedback_negative', "Wijzigen van evenement mislukt.");
                return FALSE;
            } else {
                Session::add('feedback_positive', "Pagina opgeslagen.");
                return TRUE;
            }
        } else {
            Session::add('feedback_negative', "Wijzigen van evenement mislukt. Evenement bestaat niet.");
            return FALSE;
        }
    }

}