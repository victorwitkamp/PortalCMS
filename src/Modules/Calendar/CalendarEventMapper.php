<?php
declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Database\DB;

class CalendarEventMapper
{
    /**
     * Check if an Event ID exists
     *
     * @param int $id The Id of the event
     *
     * @return bool
     */
    public static function exists($id): bool
    {
        $stmt = DB::conn()->prepare('SELECT id FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public static function getByDate($startDate, $endDate)
    {
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 00:00:00';
        $stmt = DB::conn()->prepare('SELECT * FROM events where start_event < ? and end_event > ? ORDER BY id');
        $stmt->execute([$endDateTime, $startDateTime]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    public static function getEventsAfter($dateTime)
    {
        $stmt = DB::conn()->prepare(
            'SELECT * FROM events WHERE start_event > ? ORDER BY start_event limit 3'
        );
        $stmt->execute([$dateTime]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return $stmt->fetchAll();
    }

    /**
     * Fetches an Event by Id
     *
     * @param int $id The Id of the event
     *
     * @return mixed
     */
    public static function getById($id)
    {
        $stmt = DB::conn()->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch();
        }
        return false;
    }

    public static function new($title, $start_event, $end_event, $description, $CreatedBy): bool
    {
        $stmt = DB::conn()->prepare(
            'INSERT INTO events(
                id, title, CreatedBy, start_event, end_event, description
            ) VALUES (
                NULL,?,?,?,?,?
            )'
        );
        $stmt->execute([$title, $CreatedBy, $start_event, $end_event, $description]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function update($id, $title, $start_event, $end_event, $description, $status): bool
    {
        $stmt = DB::conn()->prepare(
            'UPDATE events
            SET title=?, start_event=?, end_event=?, description=?, status=?
            WHERE id=?'
        );
        $stmt->execute([$title, $start_event, $end_event, $description, $status, $id]);
        if (!$stmt) {
            return false;
        }
        return true;
    }

    public static function updateDate($event_id, $title, $start_event, $end_event): bool
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

    public static function delete($id): bool
    {
        $stmt = DB::conn()->prepare('DELETE FROM events WHERE id = ?');
        if ($stmt->execute([$id])) {
            return true;
        }
        return false;
    }
}
