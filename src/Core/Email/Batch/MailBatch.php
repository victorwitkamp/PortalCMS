<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Batch;

use PortalCMS\Core\Database\Database;
use PortalCMS\Core\Email\Schedule\MailSchedule;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;

/**
 * Statussen: 1 Klaar voor verzending, 2 Uitgevoerd
 */
class MailBatch
{
    /**
     * @return array
     */
    public static function getAll(): array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM mail_batches ORDER BY id ');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * @return mixed
     */
    public static function lastInsertedId()
    {
        $batchId = (int)Database::conn()->query('SELECT max(id) from mail_batches')->fetchColumn();
        if (!empty($batchId)) {
            return $batchId;
        }
        return null;
    }

    /**
     * @param int|null $used_template
     * @return bool
     */
    public static function create(int $used_template = null): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO mail_batches(id, status, UsedTemplate) VALUES (NULL,1,?)');
        $stmt->execute([ $used_template ]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    /**
     * @param array $IDs
     * @return bool
     */
    public static function deleteById(array $IDs): bool
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
            Session::add('feedback_positive', 'Er zijn ' . $deleted . ' batches en ' . $deletedMessageCount . ' berichten verwijderd. ');
            Redirect::to('Email/Batches');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal batches met problemen: ' . $error);
        return false;
    }

    /**
     * @param int $batch_id
     * @return mixed
     */
    public static function countMessages(int $batch_id)
    {
        $stmt = Database::conn()->prepare('SELECT count(1) FROM mail_schedule where batch_id = ?');
        $stmt->execute([ $batch_id ]);
        return $stmt->fetchColumn();
    }

    /**
     * @param array $batch_IDs
     */
    /**
     * @param array $batch_IDs
     */
    /**
     * @param array $batch_IDs
     */
    public static function sendById(array $batch_IDs)
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
