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
                Redirect::redirectPage("events/");
            }
        }

        if (isset($_POST['updateEvent'])) {
            if (Event::updateEvent()) {
                Redirect::redirectPage("events/");
            }
        }

        if (isset($_POST['deleteEvent'])) {
            if (Event::deleteEvent()) {
                Redirect::redirectPage("events/");
            }
        }

    }
}