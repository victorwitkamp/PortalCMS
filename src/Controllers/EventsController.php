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
    protected $templates;

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function deleteEvent() : ResponseInterface
    {
        if (EventModel::delete((int)Request::post('id', true))) {
            return new RedirectResponse('/Events');
        }
        return new RedirectResponse('/Error/Error');
    }

    public function updateEvent() : ResponseInterface
    {
        if (EventModel::update(EventFactory::byUpdateRequest())) {
            return new RedirectResponse('/Events');
        }
        return new RedirectResponse('/Error/Error');
    }

    public function addEvent() : ResponseInterface
    {
        if (EventModel::create(EventFactory::byCreateRequest())) {
            return new RedirectResponse('/Events');
        }
        return new RedirectResponse('/Error/Error');
    }

    public function index() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            return new HtmlResponse($this->templates->render('Pages/Events/Index'), 200);
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function details() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int)Request::get('id'));
            if (!empty($event)) {
                return new HtmlResponse($this->templates->render('Pages/Events/Details', [ 'event' => $event ]));
            }
            Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
            return new RedirectResponse('/Error/Error');
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function add() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            return new HtmlResponse($this->templates->render('Pages/Events/Add', [
                'pageName' => (string)Text::get('TITLE_EVENTS_ADD')
            ]));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function edit() : ResponseInterface
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById((int)Request::get('id'));
            if (!empty($event)) {
                return new HtmlResponse($this->templates->render('Pages/Events/Edit', [
                    'event' => $event, 'pageName' => 'Evenement ' . $event->title . ' bewerken'
                ]));
            }
            Session::add('feedback_negative', 'Geen resultaten voor opgegeven event ID.');
            return new RedirectResponse('/Error/Error');
        }
        return new RedirectResponse('/Error/PermissionError');
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
