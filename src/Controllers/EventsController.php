<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Calendar\EventMapper;
use PortalCMS\Modules\Calendar\EventService;

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
        if (Authorization::hasPermission('events')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: details.
     */
    public function details()
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int) $_GET['id']);
            if (!empty($event)) {
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Events/Details', ['event' => $event]);
            } else {
                Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
                Redirect::to('Error/Error');
            }
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: add.
     */
    public function add()
    {
        if (Authorization::hasPermission('events')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/Add');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    /**
     * Route: edit.
     */
    public function edit()
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int) $_GET['id']);
            if (!empty($event)) {
                $pageName = 'Evenement ' . $event->title . ' bewerken';
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Events/Edit', ['event' => $event, 'pageName' => $pageName]);
            } else {
                Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
                Redirect::to('Error/Error');
            }
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function loadCalendarEvents()
    {
        echo json_encode(EventService::getByDate(
            (string)Request::get('start'),
            (string)Request::get('end')
        ));
    }

    public function loadComingEvents()
    {
        echo json_encode(EventService::loadComingEvents());
    }

    public function updateEventDate(): bool
    {
        return EventMapper::updateDate(
            (int) Request::post('id'),
            (string) Request::post('title'),
            (string) Request::post('start'),
            (string) Request::post('end')
        );
    }

    public static function deleteEvent()
    {
        if (EventService::delete((int) Request::post('id', true))) {
            Redirect::to('events/');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function updateEvent()
    {
        if (EventService::update(
            (int) Request::post('id', true),
            (string) Request::post('title', true),
            (string) Request::post('start_event', true),
            (string) Request::post('end_event', true),
            (string) Request::post('description', true),
            (int) Request::post('status', true)
        )) {
            Redirect::to('events/');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function addEvent()
    {
        if (EventService::create(
            (string) Request::post('title', true),
            (string) Request::post('start_event', true),
            (string) Request::post('end_event', true),
            (string) Request::post('description', true)
        )) {
            Redirect::to('events/');
        } else {
            Redirect::to('Error/Error');
        }
    }
}
