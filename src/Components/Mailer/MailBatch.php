<?php
// Statussen:
// 1 Klaar voor verzending
// 2 Uitgevoerd
class MailBatch
{
    public static function getAll()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_batches ORDER BY id ASC");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
    public static function getScheduled()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_batches WHERE status = 1 ORDER BY id ASC");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
    public static function lastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_batches");
        return $stmt->fetchColumn();
    }
    public static function create($used_template = null)
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_batches(id, status, UsedTemplate) VALUES (NULL,1,?)"
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
                $stmt = DB::conn()->prepare("DELETE FROM mail_batches WHERE id = ? LIMIT 1");
                $stmt->execute([$id]);
                if (!$stmt->rowCount() == 1) {
                    $error += 1;
                } else {
                    $deletedMessageCount += MailScheduleMapper::deleteByBatchId($id);
                    $deleted += 1;
                }
            }
        }
        if (!$deleted > 0) {
            Session::add('feedback_negative', "Verwijderen mislukt. Aantal batches met problemen: ".$error);
            return false;
        }
        Session::add('feedback_positive', "Er zijn ".$deleted." batches en ".$deletedMessageCount." berichten verwijderd. ");

        Redirect::mail();
    }

    public static function countMessages($batch_id)
    {
        $stmt = DB::conn()->prepare("SELECT count(1) FROM mail_schedule where batch_id = ?");
        $stmt->execute([$batch_id]);
        return $stmt->fetchColumn();
    }

    public static function sendById($batch_IDs)
    {
        $scheduledMailIDs = array();
        foreach ($batch_IDs as $batch_id) {
            $scheduledBatchMails = MailScheduleMapper::getScheduledIdsByBatchId($batch_id);
            foreach ($scheduledBatchMails as $scheduledBatchMail) {
                array_push($scheduledMailIDs, $scheduledBatchMail['id']);
            }
        }
        MailSchedule::sendbyid($scheduledMailIDs);
    }
}