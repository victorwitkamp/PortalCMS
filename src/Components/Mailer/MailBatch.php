<?php
// Statussen:
// 1 Klaar voor verzending
// 2 Uitgevoerd
class MailBatch
{
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
    public static function countMessages($batch_id)
    {
        $stmt = DB::conn()->prepare("SELECT count(1) FROM mail_schedule where batch_id = ?");
        $stmt->execute([$batch_id]);
        return $stmt->fetchColumn();
    }
    public static function sendById()
    {
        $batch_ids = Request::post('id');
        $scheduledMails = array();
        foreach ($batch_ids as $batch_id) {
            $scheduledBatchMails = MailScheduleMapper::getScheduledIdsByBatchId($batch_id);
            foreach ($scheduledBatchMails as $scheduledBatchMail) {
                array_push($scheduledMails, $scheduledBatchMail['id']);
            }
        }

        foreach ($scheduledMails as $ID) {
                MailSchedule::sendbyid($ID);

        }

    }
}