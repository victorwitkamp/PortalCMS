<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Calendar\EventFactory;
use PortalCMS\Modules\Calendar\EventMapper;
use PortalCMS\Modules\Calendar\EventModel;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EventsController
 * @package PortalCMS\Controllers
 */
class EventsController
{
    public $templates;
    public function __construct()
    {
        Authentication::checkAuthentication();
        $this->templates = new Engine(DIR_VIEW);
    }

    public function deleteEvent()
    {
        if (EventModel::delete((int)Request::post('id', true))) {
            Redirect::to('Events/');
        }
        Redirect::to('Error/Error');
    }

    public function updateEvent()
    {
        if (EventModel::update(EventFactory::byUpdateRequest())) {
            Redirect::to('Events/');
        } else {
            Redirect::to('Error/Error');
        }
    }

    public function addEvent() : ResponseInterface
    {
        if (EventModel::create(EventFactory::byCreateRequest())) {
            $response = new RedirectResponse('/Events');
        } else {
            $response = new RedirectResponse('/Error/Error');
        }
        return $response;
    }

    public function index() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $response = new HtmlResponse($this->templates->render('Pages/Events/Index'), 200);
        } else {
            $response = new RedirectResponse('/Error/PermissionError');
        }
        return $response;
    }

    public function details() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int)Request::get('id'));
            if (!empty($event)) {
                $response = new HtmlResponse($this->templates->render('Pages/Events/Details', [ 'event' => $event ]));
            } else {
                Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
                $response = new RedirectResponse('/Error/Error');
            }
        } else {
            $response = new RedirectResponse('/Error/PermissionError');
        }
        return $response;
    }

    public function add() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $response = new HtmlResponse($this->templates->render('Pages/Events/Add', [
                'pageName' => (string)Text::get('TITLE_EVENTS_ADD')
            ]));
        } else {
            $response = new RedirectResponse('/Error/PermissionError');
        }
        return $response;
    }

    public function edit() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int)Request::get('id'));
            if (!empty($event)) {
                $response = new HtmlResponse($this->templates->render('Pages/Events/Edit', [
                    'event' => $event, 'pageName' => 'Evenement ' . $event->title . ' bewerken'
                ]));
            } else {
                Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
                $response = new RedirectResponse('/Error/Error');
            }
        } else {
            $response = new RedirectResponse('/Error/PermissionError');
        }
        return $response;
    }

    public function loadCalendarEvents() : ResponseInterface
    {
        return new JsonResponse(EventModel::getByDate((string)Request::get('start'), (string)Request::get('end')));
    }

    public function updateEventDate(): bool
    {
        return EventMapper::updateDate((int)Request::post('id'), (string)Request::post('start'), (string)Request::post('end'));
    }
}
