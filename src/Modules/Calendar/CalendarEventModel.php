<?php

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Session\Session;

/**
 * Class : Event (Event.php)
 * Details : Event Class.
 */
class CalendarEventModel
{
    /**
     * @param $startDate
     * @param $endDate
     * @return array|bool
     */
    public static function getByDate(string $startDate, string $endDate)
    {
        $eventsArray = [];
        $events = CalendarEventMapper::getByDate($startDate, $endDate);
        if (!empty($events)) {
            foreach ($events as $event) {
                if ($event['status'] === '1') {
                    $color = 'var(--success)';
                } elseif ($event['status'] === '2') {
                    $color = 'var(--danger)';
                } else {
                    $color = 'var(--info)';
                }
                $eventsArray[] = [
                    'id'   => $event['id'],
                    'title'   => $event['title'],
                    'start'   => $event['start_event'],
                    'end'   => $event['end_event'],
                    'backgroundColor' => $color
                ];
            }
        }
        if (!empty($eventsArray)) {
            return $eventsArray;
        }
        return false;
    }

    /**
     * @return array|bool
     */
    public static function loadComingEvents()
    {
        $eventsArray = [];
        foreach (CalendarEventMapper::getEventsAfter(date('Y-m-d H:i:s')) as $event) {
            $eventsArray[] = [
                'id'   => $event['id'],
                'title'   => $event['title'],
                'start'   => $event['start_event'],
                'end'   => $event['end_event']
            ];
        }
        if (!empty($eventsArray)) {
            return $eventsArray;
        }
        return false;
    }

    /**
     * @return bool|string
     */
    public static function loadMailEvents()
    {
        foreach (CalendarEventMapper::getEventsAfter(date('Y-m-d H:i:s')) as $event) {
            $title = $event['title'];
            $start = $event['start_event'];
            $end = $event['end_event'];
            $description = $event['description'];
            $returndata = '';
            $returndata .= '<strong>Naam evenement: ' . $title . '</strong><br>Start: ' . $start . '<br>Einde: ' . $end . '<br><strong>Beschrijving</strong> ' . $description . '<br>';
        }
        if (!empty($returndata)) {
            return $returndata;
        }
        return false;
    }

    /**
     * @param string $title
     * @param string $start_event
     * @param string $end_event
     * @param string $description
     * @return bool
     */
    public static function create(string $title, string $start_event, string $end_event, string $description): bool
    {
        if (!CalendarEventMapper::new($title, $start_event, $end_event, $description, Session::get('user_id'))) {
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
    public static function update(int $id, string $title, string $start, string $end, string $description, int $status): bool
    {
        if (CalendarEventMapper::exists($id)) {
            if (CalendarEventMapper::update($id, $title, $start, $end, $description, $status)) {
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
        if (CalendarEventMapper::getById($id)) {
            if (CalendarEventMapper::delete($id)) {
                Session::add('feedback_positive', 'Evenement verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt.');
        }
        Session::add('feedback_negative', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
        return false;
    }
}
