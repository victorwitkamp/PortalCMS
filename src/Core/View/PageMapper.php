<?php

declare(strict_types=1);

namespace App\Core\View;

use PDO;
use App\Core\Database\Database;

class PageMapper
{
    public function exists(int $page_id): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([ $page_id ]);
        return ($stmt->rowCount() === 1);
//        $this->addFlash('danger','Pagina bestaat niet.');
    }

    public function getPage(int $page_id) : ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
        $stmt->execute([ $page_id ]);
        if ($stmt->rowCount() === 1) {
//            $this->addFlash('danger','Geen pagina gevonden voor weergave.');
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function updatePage(int $page_id, string $content): bool
    {
        if ($this->exists($page_id)) {
            $stmt = Database::conn()->prepare('UPDATE pages
                                SET content=?
                                    WHERE id=?');
            if ($stmt->execute([ $content, $page_id ])) {
//                $this->addFlash('success','Pagina opgeslagen.');
                return true;
            }
//            $this->addFlash('danger','Wijzigen van evenement mislukt.');
//            return false;
        }
//        $this->addFlash('danger','Wijzigen van evenement mislukt. Evenement bestaat niet.');
        return false;
    }
}
