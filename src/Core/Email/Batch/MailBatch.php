<?php

namespace PortalCMS\Core\Email\Batch;

use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Email\Schedule\MailSchedule;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\Session\Session;

/**
 * Statussen: 1 Klaar voor verzending, 2 Uitgevoerd
 */
class MailBatch
{
    public static function getAll()
    {
        $stmt = DB::conn()->prepare('SELECT * FROM mail_batches ORDER BY id ASC');
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * @return mixed
     */
    public static function lastInsertedId()
    {
        return DB::conn()->query('SELECT max(id) from mail_batches')->fetchColumn();
    }

    /**
     * @param null $used_template
     * @return bool
     */
    public static function create($used_template = null)
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_batches(id, status, UsedTemplate) VALUES (NULL,1,?)'
        );
        $stmt->execute([$used_template]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function deleteById($IDs)
    {
        $deleted = 0;
        $error = 0;
        $deletedMessageCount = 0;

        if (!empty($IDs)) {
            foreach ($IDs as $id) {
                $stmt = DB::conn()->prepare('DELETE FROM mail_batches WHERE id = ? LIMIT 1');
                $stmt->execute([$id]);
                if ($stmt->rowCount() === 1) {
                    $deletedMessageCount += MailScheduleMapper::deleteByBatchId($id);
                    ++$deleted;
                } else {
                    ++$error;
                }
            }
        }
        if ($deleted > 0) {
            Session::add('feedback_positive', 'Er zijn ' . $deleted . ' batches en ' . $deletedMessageCount . ' berichten verwijderd. ');
            Redirect::to('mail');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen mislukt. Aantal batches met problemen: ' . $error);
        return false;
    }

    public static function countMessages($batch_id)
    {
        $stmt = DB::conn()->prepare('SELECT count(1) FROM mail_schedule where batch_id = ?');
        $stmt->execute([$batch_id]);
        return $stmt->fetchColumn();
    }

    public static function sendById($batch_IDs)
    {
        $scheduledMailIDs = [];
        foreach ($batch_IDs as $batch_id) {
            foreach (MailScheduleMapper::getScheduledIdsByBatchId($batch_id) as $scheduledBatchMail) {
                $scheduledMailIDs[] = $scheduledBatchMail['id'];
            }
        }
        MailSchedule::sendMailsById($scheduledMailIDs);
    }
}
