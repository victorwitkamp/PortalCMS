<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\HTTP\Redirect;
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
            if (CalendarEventModel::create()) {
                Redirect::to("events/");
            }
        }

        if (isset($_POST['updateEvent'])) {
            if (CalendarEventModel::update()) {
                Redirect::to("events/");
            }
        }

        if (isset($_POST['deleteEvent'])) {
            if (CalendarEventModel::delete()) {
                Redirect::to("events/");
            }
        }
    }
}
