<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PDO;
use PortalCMS\Core\Database\DB;

class CalendarEventMapper
{
    /**
     * Check if an Event ID exists
     * @param int $id The Id of the event
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public static function getByDate(string $startDate, string $endDate) : ?array
    {
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 00:00:00';
        $stmt = DB::conn()->prepare('SELECT * FROM events where start_event < ? and end_event > ? ORDER BY id');
        $stmt->execute([$endDateTime, $startDateTime]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getEventsAfter(string $dateTime) : ?array
    {
        $stmt = DB::conn()->prepare(
            'SELECT * FROM events WHERE start_event > ? ORDER BY start_event limit 3'
        );
        $stmt->execute([$dateTime]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Fetches an Event by Id
     * @param int $id The Id of the event
     * @return object|null
     */
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
            ) VALUES (
                NULL,?,?,?,?,?
            )'
        );
        $stmt->execute([
            $title,
            $start_event,
            $end_event,
            $description,
            $CreatedBy]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function update(Event $event): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE events
            SET title=?, start_event=?, end_event=?, description=?, status=?
            WHERE id=?'
        );
        $stmt->execute([$event->title, $event->start_event, $event->end_event, $event->description, $event->status, $event->id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateDate(int $event_id, string $title, string $start_event, string $end_event): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE events
            SET title=?, start_event=?, end_event=?
            WHERE id=?'
        );
        $stmt->execute([$title, $start_event, $end_event, $event_id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function delete(int $id): bool
    {
        $stmt = DB::conn()->prepare('DELETE FROM events WHERE id = ?');
        if ($stmt->execute([$id])) {
            return true;
        }
        return false;
    }
}
