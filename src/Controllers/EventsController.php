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
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Calendar\CalendarEventModel;
use PortalCMS\Modules\Calendar\CalendarEventMapper;

/**
 * EventsController
 */
class EventsController extends Controller
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

    /**
     * Route: index.
     */
    public function index()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('events');
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Events/index');
    }

    /**
     * Route: details.
     */
    public function details()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('events');
        $event = CalendarEventMapper::getById((int) $_GET['id']);
        if (!empty($event)) {
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/details', ['event' => $event]);
        }
    }

    public function add()
    {
        $templates = new \League\Plates\Engine(DIR_VIEW);
        echo $templates->render('Pages/Events/add');
    }

    public function edit()
    {
        Authentication::checkAuthentication();
        Authorization::verifyPermission('events');

        $event = CalendarEventMapper::getById((int) $_GET['id']);

        if (!empty($event)) {
            $pageName = 'Evenement ' . $event->title . ' bewerken';
            $templates = new \League\Plates\Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/edit', ['event' => $event, 'pageName' => $pageName]);
        } else {
            Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
            Redirect::to('includes/error.php');
        }
    }

    public function loadCalendarEvents()
    {
        Authentication::checkAuthentication();
        echo json_encode(CalendarEventModel::getByDate(Request::get('start'), Request::get('end')));
    }

    public function loadComingEvents()
    {
        Authentication::checkAuthentication();
        echo json_encode(CalendarEventModel::loadComingEvents());
    }

    public function updateEventDate()
    {
        Authentication::checkAuthentication();
        return CalendarEventMapper::updateDate(
            (int) Request::post('id'),
            (string) Request::post('title'),
            (string) Request::post('start'),
            (string) Request::post('end')
        );
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
