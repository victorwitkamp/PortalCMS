<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\Session\Session;

class EventModel
{
    public static function getByDate(string $startDate, string $endDate): array
    {
        $eventsArray = [];
        $events = EventMapper::getByDate($startDate, $endDate);
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
        $events = EventMapper::getEventsAfter(date('Y-m-d H:i:s'));
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

    public static function create(Event $event): bool
    {
        if (EventMapper::new($event)) {
            Session::add('feedback_positive', 'Evenement toegevoegd.');
            return true;
        }
        Session::add('feedback_negative', 'Toevoegen van evenement mislukt.');
        return false;
    }

    public static function update(Event $event): bool
    {
        if (!EventMapper::exists($event->id)) {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt. Evenement bestaat niet.');
        } elseif (EventMapper::update(
            $event
        )) {
            Activity::add('UpdateEvent', Session::get('user_id'), 'ID: ' . $event->id, Session::get('user_name'));
            Session::add('feedback_positive', 'Evenement gewijzigd.');
            return true;
        } else {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.');
        }
        return false;
    }

    public static function delete(int $id): bool
    {
        if (empty(EventMapper::getById($id))) {
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
        } elseif (EventMapper::delete($id)) {
            Session::add('feedback_positive', 'Evenement verwijderd.');
            return true;
        } else {
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt.');
        }
        return false;
    }
}
