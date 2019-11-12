<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Modules\Calendar\CalendarEventModel;

/**
 * EventController
 */
class EventController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'addEvent' => 'POST',
        'updateEvent' => 'POST',
        'deleteEvent' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function deleteEvent()
    {
        if (CalendarEventModel::delete((int) Request::post('id', true))) {
            Redirect::to('events/');
        } else {
            Redirect::to('includes/error.php');
        }
    }

    public static function updateEvent()
    {
        if (CalendarEventModel::update(
            (int) Request::post('id', true),
            (string) Request::post('title', true),
            (string) Request::post('start_event', true),
            (string) Request::post('end_event', true),
            (string) Request::post('description', true),
            (int) Request::post('status', true)
        )) {
            Redirect::to('events/');
        } else {
            Redirect::to('includes/error.php');
        }
    }

    public static function addEvent()
    {
        if (CalendarEventModel::create(
            (string) Request::post('title', true),
            (string) Request::post('start_event', true),
            (string) Request::post('end_event', true),
            (string) Request::post('description', true)
        )) {
            Redirect::to('events/');
        } else {
            Redirect::to('includes/error.php');
        }
    }
}
