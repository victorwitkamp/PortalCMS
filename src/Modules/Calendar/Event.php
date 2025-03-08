<?php

declare(strict_types=1);

namespace App\Modules\Calendar;

use App\Core\Session\Session;

class Event
{
    public int $id;
    public int $CreatedBy;
    public string $title;
    public string $start_event;
    public string $end_event;
    public string $description;
    public int $status;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setCreatedBy()
    {
        $this->CreatedBy = (int) Session::get('user_id');
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
