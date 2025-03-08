<?php


declare(strict_types=1);

namespace App\Core\Email\Batch;

use App\Core\Database\Database;
use App\Core\Email\Schedule\MailSchedule;
use App\Core\Email\Schedule\MailScheduleMapper;
use App\Core\HTTP\Redirect;

/**
 * Statussen: 1 Klaar voor verzending, 2 Uitgevoerd
 */
class MailBatch
{
    public function getAll(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_batches ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    public function lastInsertedId() : ?int
    {
        $batchId = (int) Database::conn()->query('SELECT max(id) from mail_batches')->fetchColumn();
        if (!empty($batchId)) {
            return $batchId;
        }
        return null;
    }

    public function create(int $used_template = null): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_batches(id, status, UsedTemplate) VALUES (NULL,1,?)');
        $stmt->execute([ $used_template ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public function deleteById(array $IDs): bool
    {
        $deleted = 0;
        $error = 0;
        $deletedMessageCount = 0;

        if (!empty($IDs)) {
            foreach ($IDs as $id) {
                $stmt = Database::conn()->prepare('DELETE FROM mail_batches WHERE id = ? LIMIT 1');
                $stmt->execute([ (int)$id ]);
                if ($stmt->rowCount() === 1) {
                    $deletedMessageCount += MailScheduleMapper::deleteByBatchId((int)$id);
                    ++$deleted;
                } else {
                    ++$error;
                }
            }
        }
        if ($deleted > 0) {
            $this->addFlash('success','Er zijn ' . $deleted . ' batches en ' . $deletedMessageCount . ' berichten verwijderd. ');
            Redirect::to('Email/Batches');
            return true;
        }
        $this->addFlash('danger','Verwijderen mislukt. Aantal batches met problemen: ' . $error);
        return false;
    }

    public function countMessages(int $batch_id)
    {
        $stmt = Database::conn()->prepare('SELECT count(1) FROM mail_schedule where batch_id = ?');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchColumn();
    }

    public function sendById(array $batch_IDs)
    {
        $scheduledMailIDs = [];
        foreach ($batch_IDs as $batch_id) {
            foreach (MailScheduleMapper::getScheduledIdsByBatchId((int)$batch_id) as $scheduledBatchMail) {
                $scheduledMailIDs[] = $scheduledBatchMail['id'];
            }
        }
        MailSchedule::sendMailsById($scheduledMailIDs);
    }
}
