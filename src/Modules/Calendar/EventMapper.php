<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PDO;
use PortalCMS\Core\Database\Database;

/**
 * Class EventMapper
 * @package PortalCMS\Modules\Calendar
 */
class EventMapper
{
    /**
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $stmt = Database::conn()->prepare('SELECT id FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return array|null
     */
    public static function getByDate(string $startDate, string $endDate): ?array
    {
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 00:00:00';
        $stmt = Database::conn()->prepare('SELECT * FROM events where start_event < ? and end_event > ? ORDER BY id');
        $stmt->execute([ $endDateTime, $startDateTime ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * @param string $dateTime
     * @param int    $limit
     * @return array|null
     */
    public static function getEventsAfter(string $dateTime, int $limit = 3): ?array
    {
        $stmt = Database::conn()->prepare('SELECT * FROM events WHERE start_event > ? ORDER BY start_event limit ?');
        $stmt->execute([ $dateTime, $limit ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * @param int $id
     * @return object|null
     */
    public static function getById(int $id): ?object
    {
        $stmt = Database::conn()->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
        $stmt->execute([ $id ]);
        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public static function new(Event $event): bool
    {
        $stmt = Database::conn()->prepare('INSERT INTO events(
                id, title, start_event, end_event, description, CreatedBy
            ) VALUES (NULL,?,?,?,?,?)');
        $stmt->execute([ $event->title, $event->start_event, $event->end_event, $event->description, $event->CreatedBy ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param Event $event
     * @return bool
     */
    public static function update(Event $event): bool
    {
        $stmt = Database::conn()->prepare('UPDATE events
            SET title=?, start_event=?, end_event=?, description=?, status=?
            WHERE id=?');
        $stmt->execute([ $event->title, $event->start_event, $event->end_event, $event->description, $event->status, $event->id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int    $event_id
     * @param string $start_event
     * @param string $end_event
     * @return bool
     */
    /**
     * @param int    $event_id
     * @param string $start_event
     * @param string $end_event
     * @return bool
     */
    /**
     * @param int    $event_id
     * @param string $start_event
     * @param string $end_event
     * @return bool
     */
    public static function updateDate(int $event_id, string $start_event, string $end_event): bool
    {
        $stmt = Database::conn()->prepare('UPDATE events
            SET start_event=?, end_event=?
            WHERE id=?');
        $stmt->execute([ $start_event, $end_event, $event_id ]);
        return ($stmt->rowCount() === 1);
    }

    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = Database::conn()->prepare('DELETE FROM events 
                        WHERE id = ?
                            LIMIT 1');
        $stmt->execute([ $id ]);
        return ($stmt->rowCount() === 1);
    }
}
