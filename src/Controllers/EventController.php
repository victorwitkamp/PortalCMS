<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Core\Controllers\Controller;

/**
 * EventController
 * Controls everything that is event-related
 */
class EventController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['addEvent'])) {
            $title = Request::post('title', true);
            $start_event = Request::post('start_event', true);
            $end_event = Request::post('end_event', true);
            $description = Request::post('description', true);
            if (CalendarEventModel::create($title, $start_event, $end_event, $description)) {
                Redirect::to('events/');
            } else {
                Redirect::error();
            }
        }

        if (isset($_POST['updateEvent'])) {
            $event_id = Request::post('id', true);
            $title = Request::post('title', true);
            $start_event = Request::post('start_event', true);
            $end_event = Request::post('end_event', true);
            $description = Request::post('description', true);
            $status = Request::post('status', true);
            if (CalendarEventModel::update($event_id, $title, $start_event, $end_event, $description, $status)) {
                Redirect::to('events/');
            } else {
                Redirect::error();
            }
        }

        if (isset($_POST['deleteEvent'])) {
            $id = Request::post('id', true);
            if (CalendarEventModel::delete($id)) {
                Redirect::to('events/');
            } else {
                Redirect::error();
            }
        }
    }
}
