<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\View;

use PDO;
use PortalCMS\Core\Database\Database;
use PortalCMS\Core\HTTP\Session;

/**
 * Page class.
 */
class Page
{
    /**
     */
    public static function checkPage(int $page_id): bool
    {
        $stmt = Database::conn()->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() === 1) {
            return true;
        }
        Session::add('feedback_negative', 'Pagina bestaat niet.');
        return false;
    }

    /**
     * @return bool|mixed
     */
    public static function getPage(int $page_id)
    {
        $stmt = Database::conn()->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() !== 1) {
            Session::add('feedback_negative', 'Geen pagina gevonden voor weergave.');
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     */
    public static function updatePage(int $page_id, string $content): bool
    {
        $stmt = Database::conn()->prepare('SELECT id
                            FROM pages
                                WHERE id = ? LIMIT 1');
        $stmt->execute([$page_id]);
        if ($stmt->rowCount() === 1) {
            $stmt = Database::conn()->prepare('UPDATE pages
                                SET content=?
                                    WHERE id=?');
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
