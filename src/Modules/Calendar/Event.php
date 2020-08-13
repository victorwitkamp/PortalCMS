<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Modules\Calendar;

use PortalCMS\Core\Session\Session;

/**
 * Class Event
 * @package PortalCMS\Modules\Calendar
 */
class Event
{
    public $id;
    public $CreatedBy;
    public $title;
    public $start_event;
    public $end_event;
    public $description;
    public $status;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setCreatedBy()
    {
        $this->CreatedBy = (int)Session::get('user_id');
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start)
    {
        $this->start_event = date('Y-m-d H:i:s', strtotime($start));
    }

    /**
     * @param string $end
     */
    public function setEnd(string $end)
    {
        $this->end_event = date('Y-m-d H:i:s', strtotime($end));
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}
