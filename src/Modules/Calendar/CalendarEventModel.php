<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Session\Session;

class CalendarEventModel
{
    /**
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function getByDate(string $startDate, string $endDate): array
    {
        $eventsArray = [];
        $events = CalendarEventMapper::getByDate($startDate, $endDate);
        if (!empty($events)) {
            foreach ($events as $event) {
                if ($event->status === 1) {
                    $color = 'var(--success)';
                } elseif ($event->status === 2) {
                    $color = 'var(--danger)';
                } else {
                    $color = 'var(--info)';
                }
                $eventsArray[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_event,
                    'end' => $event->end_event,
                    'backgroundColor' => $color
                ];
            }
        }
        return $eventsArray;
    }

    /**
     * @return array|bool
     */
    public static function loadComingEvents()
    {
        $eventsArray = [];
        $events = CalendarEventMapper::getEventsAfter(date('Y-m-d H:i:s'));
        if (!empty($events)) {
            foreach ($events as $event) {
                $eventsArray[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_event,
                    'end' => $event->end_event
                ];
            }
        }
        return $eventsArray;
    }

    /**
     * @param string $title
     * @param string $start_event
     * @param string $end_event
     * @param string $description
     * @return bool
     */
    public static function create(string $title, string $start, string $end, string $description): bool
    {
        if (!CalendarEventMapper::new($title, date('Y-m-d H:i:s', strtotime($start)), date('Y-m-d H:i:s', strtotime($end)), $description, (int)Session::get('user_id'))) {
            Session::add('feedback_negative', 'Toevoegen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Evenement toegevoegd.');
        return true;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $start
     * @param string $end
     * @param string $description
     * @param int $status
     * @return bool
     */
    public static function update(Event $event): bool
    {
        if (CalendarEventMapper::exists($event->id)) {
            if (CalendarEventMapper::update($event->id, $event->title, date('Y-m-d H:i:s', strtotime($event->start)), date('Y-m-d H:i:s', strtotime($event->end)), $event->description, $event->status)) {
                Session::add('feedback_positive', 'Evenement gewijzigd.');
                return true;
            }
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.');
        } else {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt. Evenement bestaat niet.');
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        if (!empty(CalendarEventMapper::getById($id))) {
            if (CalendarEventMapper::delete($id)) {
                Session::add('feedback_positive', 'Evenement verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
        }
        return false;
    }
}
