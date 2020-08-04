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

class Event
{
    public $id;
    public $CreatedBy;
    public $title;
    public $start_event;
    public $end_event;
    public $description;
    public $status;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setCreatedBy()
    {
        $this->CreatedBy = (int)Session::get('user_id');
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setStart(string $start)
    {
        $this->start_event = date('Y-m-d H:i:s', strtotime($start));
    }

    public function setEnd(string $end)
    {
        $this->end_event = date('Y-m-d H:i:s', strtotime($end));
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}
