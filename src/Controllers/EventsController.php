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
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Calendar\EventFactory;
use PortalCMS\Modules\Calendar\EventMapper;
use PortalCMS\Modules\Calendar\EventModel;

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

    public function index()
    {
        if (Authorization::hasPermission('events')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/Index');
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

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

    public function add()
    {
        if (Authorization::hasPermission('events')) {
            $templates = new Engine(DIR_VIEW);
            echo $templates->render('Pages/Events/Add', [
                'pageName' => (string) Text::get('TITLE_EVENTS_ADD')
            ]);
        } else {
            Redirect::to('Error/PermissionError');
        }
    }

    public function edit()
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int) Request::get('id'));
            if (!empty($event)) {
                $templates = new Engine(DIR_VIEW);
                echo $templates->render('Pages/Events/Edit', [
                    'event' => $event,
                    'pageName' => 'Evenement ' . $event->title . ' bewerken'
                ]);
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
        echo json_encode(EventModel::getByDate((string) Request::get('start'), (string) Request::get('end')));
    }

    public function updateEventDate(): bool
    {
        return EventMapper::updateDate(
            (int) Request::post('id'),
            (string) Request::post('start'),
            (string) Request::post('end')
        );
    }

    public static function deleteEvent()
    {
        if (EventModel::delete(
            (int) Request::post('id', true)
        )) {
            Redirect::to('Events/');
        }
        Redirect::to('Error/Error');
    }

    public static function updateEvent()
    {
        if (EventModel::update(EventFactory::byUpdateRequest())) {
            Redirect::to('Events/');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public static function addEvent()
    {
        if (EventModel::create(EventFactory::byCreateRequest())) {
            Redirect::to('Events/');
        } else {
            Redirect::to('Error/Error');
        }
    }
}
