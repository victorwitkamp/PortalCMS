<?php

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
            if (Event::addEvent()) {
                Redirect::to("events/");
            }
        }

        if (isset($_POST['updateEvent'])) {
            if (Event::updateEvent()) {
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