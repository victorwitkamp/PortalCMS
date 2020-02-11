<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PDO;
use PortalCMS\Core\Database\DB;

class EventMapper
{
    public static function exists(int $id): bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }

    public static function getByDate(string $startDate, string $endDate) : ?array
    {
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 00:00:00';
        $stmt = DB::conn()->prepare('SELECT * FROM events where start_event < ? and end_event > ? ORDER BY id');
        $stmt->execute([$endDateTime, $startDateTime]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getEventsAfter(string $dateTime) : ?array
    {
        $stmt = DB::conn()->prepare(
            'SELECT * FROM events WHERE start_event > ? ORDER BY start_event limit 3'
        );
        $stmt->execute([$dateTime]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function getById(int $id) : ?object
    {
        $stmt = DB::conn()->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function new(string $title, string $start_event, string $end_event, string $description, int $CreatedBy) : bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO events(
                id, title, start_event, end_event, description, CreatedBy
            ) VALUES (NULL,?,?,?,?,?)'
        );
        $stmt->execute([$title, $start_event, $end_event, $description, $CreatedBy]);
        return ($stmt->rowCount() === 1);
    }

    public static function update(string $title, string $start_event, string $end_event, string $description, int $status, int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE events
            SET title=?, start_event=?, end_event=?, description=?, status=?
            WHERE id=?'
        );
        $stmt->execute([$title, $start_event, $end_event, $description, $status, $id]);
        return ($stmt->rowCount() === 1);
    }

    public static function updateDate(int $event_id, string $title, string $start_event, string $end_event): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE events
            SET title=?, start_event=?, end_event=?
            WHERE id=?'
        );
        $stmt->execute([$title, $start_event, $end_event, $event_id]);
        return ($stmt->rowCount() === 1);
    }

    public static function delete(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            'DELETE FROM events 
                        WHERE id = ?
                            LIMIT 1'
        );
        $stmt->execute([$id]);
        return ($stmt->rowCount() === 1);
    }
}
