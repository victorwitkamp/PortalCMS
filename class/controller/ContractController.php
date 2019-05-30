<?php

/**
 * ContractController
 * Controls everything that is event-related
 */
class EventController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['updateContract'])) {
            Contract::update();
        }
        if (isset($_POST['newContract'])) {
            Contract::new();
        }
        if (isset($_POST['deleteContract'])) {
            Contract::delete();
        }

    }
}