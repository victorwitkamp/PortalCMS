<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Models\Event;

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
            if (Event::create()) {
                Redirect::to("events/");
            }
        }

        if (isset($_POST['updateEvent'])) {
            if (Event::update()) {
                Redirect::to("events/");
            }
        }

        if (isset($_POST['deleteEvent'])) {
            if (Event::delete()) {
                Redirect::to("events/");
            }
        }
    }
}
