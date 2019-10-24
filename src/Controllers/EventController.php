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
            self::createHandler();
        }

        if (isset($_POST['updateEvent'])) {
            self::updateHandler();
        }

        if (isset($_POST['deleteEvent'])) {
            self::deleteHandler();
        }
    }

    public static function deleteHandler()
    {
        $id = (int) Request::post('id', true);
        if (CalendarEventModel::delete($id)) {
            Redirect::to('events/');
        } else {
            Redirect::error();
        }
    }

    public static function updateHandler()
    {
        $event_id = (int) Request::post('id', true);
        $title = (string) Request::post('title', true);
        $start_event = (string) Request::post('start_event', true);
        $end_event = (string) Request::post('end_event', true);
        $description = (string) Request::post('description', true);
        $status = (int) Request::post('status', true);
        if (CalendarEventModel::update($event_id, $title, $start_event, $end_event, $description, $status)) {
            Redirect::to('events/');
        } else {
            Redirect::error();
        }
    }

    public static function createHandler()
    {
        $title = (string) Request::post('title', true);
        $start_event = (string) Request::post('start_event', true);
        $end_event = (string) Request::post('end_event', true);
        $description = (string) Request::post('description', true);
        if (CalendarEventModel::create($title, $start_event, $end_event, $description)) {
            Redirect::to('events/');
        } else {
            Redirect::error();
        }
    }
}
