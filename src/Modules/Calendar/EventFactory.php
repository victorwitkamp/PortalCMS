<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\HTTP\Request;

/**
 * Class EventFactory
 * @package PortalCMS\Modules\Calendar
 */
class EventFactory
{
    /**
     */
    public static function byCreateRequest(): Event
    {
        $event = new Event();
        $event->setCreatedBy();
        $event->setTitle((string)Request::post('title', true));
        $event->setStart((string)Request::post('start_event', true));
        $event->setEnd((string)Request::post('end_event', true));
        $event->setDescription((string)Request::post('description', true));
        return $event;
    }

    /**
     */
    public static function byUpdateRequest(): Event
    {
        $event = new Event();
        $event->setId((int)Request::post('id', true));
        $event->setTitle((string)Request::post('title', true));
        $event->setStart((string)Request::post('start_event', true));
        $event->setEnd((string)Request::post('end_event', true));
        $event->setDescription((string)Request::post('description', true));
        $event->setStatus((int)Request::post('status', true));
        return $event;
    }
}
