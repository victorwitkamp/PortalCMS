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
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Calendar\EventMapper;
use PortalCMS\Modules\Calendar\EventService;

class EventsController extends Controller
{
    private $requests = [
        'addEvent' => 'POST',
        'updateEvent' => 'POST',
        'deleteEvent' => 'POST'
    ];

    public function __construct()
    {
        parent::__construct();
        Authentication::checkAuthentication();
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
            $event = EventMapper::getById((int) Request::get('id'));
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
            $event = EventMapper::getById((int) Request::get('id'));
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
        echo json_encode(EventService::getByDate((string) Request::get('start'), (string) Request::get('end')));
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
        if (EventService::delete(
            (int) Request::post('id', true)
        )) {
            Redirect::to('Events/');
        }
        Redirect::to('Error/Error');
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
            Redirect::to('Events/');
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
            Redirect::to('Events/');
        } else {
            Redirect::to('Error/Error');
        }
    }
}
