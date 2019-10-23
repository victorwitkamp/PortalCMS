<?php

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Database\DB;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

/**
 * Class : Event (Event.php)
 * Details : Event Class.
 */
class CalendarEventModel
{
    public static function loadCalendarEvents($startDate, $endDate)
    {
        $result = CalendarEventMapper::getByDate($startDate, $endDate);
        if (!empty($result)) {
            foreach ($result as $row) {
                if ($row['status'] === '1') {
                    $color = 'var(--success)';
                } elseif ($row['status'] === '2') {
                    $color = 'var(--danger)';
                } else {
                    $color = 'var(--info)';
                }
                $data[] = array(
                    'id'   => $row['id'],
                    'title'   => $row['title'],
                    'start'   => $row['start_event'],
                    'end'   => $row['end_event'],
                    'backgroundColor' => $color
                );
            }
        }
        if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo '{}';

        }
    }

    public static function loadComingEvents()
    {
        $now = date('Y-m-d H:i:s');
        foreach (CalendarEventMapper::getEventsAfter($now) as $row) {
            $data[] = array(
                'id'   => $row['id'],
                'title'   => $row['title'],
                'start'   => $row['start_event'],
                'end'   => $row['end_event']
            );
        }
        $returndata = json_encode($data);
        if (!empty($returndata)) {
            echo $returndata;
        }
    }

    public static function loadMailEvents()
    {
        $now = date('Y-m-d H:i:s');
        foreach (CalendarEventMapper::getEventsAfter($now) as $row) {
            $title = $row['title'];
            $start = $row['start_event'];
            $end = $row['end_event'];
            $description = $row['description'];
            $returndata = '';
            $returndata .= '<strong>Naam evenement: ' . $title . '</strong><br>Start: ' . $start . '<br>Einde: ' . $end . '<br><strong>Beschrijving</strong> ' . $description . '<br>';
        }
        if (!empty($returndata)) {
            return $returndata;
        }
        return false;
    }

    public static function create()
    {
        $title = Request::post('title', true);
        $start_event = Request::post('start_event', true);
        $end_event = Request::post('end_event', true);
        $description = Request::post('description', true);
        $stmt = DB::conn()->prepare(
            'SELECT id
            FROM events
            WHERE start_event = ?
            AND end_event = ?'
        );
        $stmt->execute([$start_event, $end_event]);
        if (!$stmt->rowCount() == 0) {
            Session::add('feedback_negative', 'Kies een andere tijd.');
            return false;
        }
        $CreatedBy = Session::get('user_id');
        if (!CalendarEventMapper::new($title, $start_event, $end_event, $description, $CreatedBy)) {
            Session::add('feedback_negative', 'Toevoegen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Evenement toegevoegd.');
        return true;
    }

    public static function update()
    {
        $event_id = Request::post('id', true);
        $title = Request::post('title', true);
        $start_event = Request::post('start_event', true);
        $end_event = Request::post('end_event', true);
        $description = Request::post('description', true);
        $status = Request::post('status', true);
        if (!CalendarEventMapper::exists($event_id)) {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.<br>Evenement bestaat niet.');
            return false;
        }
        if (!CalendarEventMapper::update($event_id, $title, $start_event, $end_event, $description, $status)) {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Evenement gewijzigd.');
        return true;
    }

    public static function delete()
    {
        $id = Request::post('id', true);
        $event = CalendarEventMapper::getById($id);
        if (!$event) {
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
            return false;
        }
        if (CalendarEventMapper::delete($id)) {
            Session::add('feedback_positive', 'Evenement verwijderd.');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen van evenement mislukt.');
        return false;
    }
}
