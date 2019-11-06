<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Page;

use PDO;
use PortalCMS\Core\Database\DB;
use PortalCMS\Core\Session\Session;

/**
 * Class : Page (Page.class.php)
 * Details : Page Class.
 */

class Page
{
    public static function checkPage($page_id): bool
    {
        $stmt = DB::conn()->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() === 1) {
            return true;
        }
        Session::add('feedback_negative', 'Pagina bestaat niet.');
        return false;
    }

    public static function getPage($page_id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() !== 1) {
            Session::add('feedback_negative', 'Geen pagina gevonden voor weergave.');
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public static function updatePage($page_id, $content): bool
    {
        $stmt = DB::conn()->prepare(
            'SELECT id 
                            FROM pages 
                                WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() === 1) {
            $stmt = DB::conn()->prepare(
                'UPDATE pages
                                SET content=? 
                                    WHERE id=?'
            );
            if ($stmt->execute([$content, $page_id])) {
                Session::add('feedback_positive', 'Pagina opgeslagen.');
                return true;
            }
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_negative', 'Wijzigen van evenement mislukt. Evenement bestaat niet.');
        return false;
    }
}
