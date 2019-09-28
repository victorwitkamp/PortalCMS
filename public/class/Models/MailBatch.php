<?php
// Statussen:
// 1 Klaar voor verzending
// 2 Uitgevoerd
class MailBatch {
    public static function getScheduled()
    {
        $stmt = DB::conn()->prepare("SELECT * FROM mail_batches WHERE status = 0 ORDER BY id ASC");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }
    public static function lastInsertedId()
    {
        $stmt = DB::conn()->query("SELECT max(id) from mail_batches");
        return $stmt->fetchColumn();
    }
    public static function create() {
        $stmt = DB::conn()->prepare(
            "INSERT INTO mail_batches(id, status) VALUES (NULL,0)"
        );
        $stmt->execute();
        if (!$stmt) {
            return false;
        }
        return true;
    }
}