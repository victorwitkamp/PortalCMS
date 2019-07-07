<?php
/**
 * Class : Event (Event.php)
 * Details : Event Class.
 */
class Event
{
    public static function loadEvents($startDate, $endDate)
    {
        $result = EventMapper::getByDate($startDate, $endDate);
        $data = array();
        foreach ($result as $row) {
            if ($row['status'] == 0) {
                $color = 'var(--info)';
            }
            if ($row['status'] == 1) {
                $color ='var(--success)';
            }
            if ($row['status'] == 2) {
                $color = 'var(--danger)';
            }
            $data[] = array(
                'id'   => $row["id"],
                'title'   => $row["title"],
                'start'   => $row["start_event"],
                'end'   => $row["end_event"],
                'backgroundColor' => $color
            );
        }
        $returndata = json_encode($data);
        if (!empty($returndata)) {
            echo $returndata;
        }
    }

    // public static function loadComingEvents()
    // {
    //     $now = date("Y-m-d H:i:s");
    //     $result = EventMapper::getEventsAfter($now);
    //     foreach ($result as $row) {
    //         $data[] = array(
    //             'id'   => $row["id"],
    //             'title'   => $row["title"],
    //             'start'   => $row["start_event"],
    //             'end'   => $row["end_event"]
    //         );
    //     }
    //     $returndata = json_encode($data);
    //     if (!empty($returndata)) {
    //         echo $returndata;
    //     }
    // }

    public static function loadStaticComingEvents()
    {
        $now = date("Y-m-d H:i:s");
        $result = EventMapper::getEventsAfter($now);
        foreach ($result as $row) {
            $title = $row["title"];
            $start = $row["start_event"];
            $end = $row["end_event"];
            $description = $row["description"];
            $returndata = '';
            $returndata .= '<strong>Naam evenement: '.$title.'</strong><br>Start: '.$start.'<br>Einde: '.$end.'<br><strong>Beschrijving</strong> '.$description.'<br>';
        }
        if (!empty($returndata)) {
            return $returndata;
        }
        return false;
    }

    public static function addEvent()
    {
        $title = Request::post('title', true);
        $start_event = Request::post('start_event', true);
        $end_event = Request::post('end_event', true);
        $description = Request::post('description', true);
        $stmt = DB::conn()->prepare(
            "SELECT id
            FROM events
            WHERE start_event = ?
            AND end_event = ?"
        );
        $stmt->execute([$start_event, $end_event]);
        if (!$stmt->rowCount() == 0) {
            Session::add('feedback_negative', 'Kies een andere tijd.');
            return false;
        }
        $CreatedBy = Session::get('user_id');
        if (!EventMapper::new($title, $start_event, $end_event, $description, $CreatedBy)) {
            Session::add('feedback_negative', 'Toevoegen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Evenement toegevoegd.');
        return true;
    }

    public static function updateEvent()
    {
        $event_id = Request::post('id', true);
        $title = Request::post('title', true);
        $start_event = Request::post('start_event', true);
        $end_event = Request::post('end_event', true);
        $description = Request::post('description', true);
        $status = Request::post('status', true);
        if (!EventMapper::exists($event_id)) {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.<br>Evenement bestaat niet.');
            return false;
        }
        if (!EventMapper::update($event_id, $title, $start_event, $end_event, $description, $status)) {
            Session::add('feedback_negative', 'Wijzigen van evenement mislukt.');
            return false;
        }
        Session::add('feedback_positive', 'Evenement gewijzigd.');
        return true;
    }

    public static function delete()
    {
        $id = Request::post('id', true);
        $event = EventMapper::getById($id);
        if (!$event) {
            Session::add('feedback_negative', 'Verwijderen van evenement mislukt.<br>Evenement bestaat niet.');
            return false;
        }
        if (EventMapper::delete($id)) {
            Session::add('feedback_positive', 'Evenement verwijderd.');
            return true;
        }
        Session::add('feedback_negative', 'Verwijderen van evenement mislukt.');
        return false;
    }



}
