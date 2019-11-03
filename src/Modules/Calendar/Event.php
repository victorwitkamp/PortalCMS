<?php

namespace PortalCMS\Modules\Calendar;

class Event
{
    public $id;
    public $CreatedBy;
    public $title;
    public $start_event;
    public $end_event;
    public $description;
    public $status;
    public $CreationDate;
    public $ModificationDate;

    /**
     * Event constructor.
     * @param int $id
     * @param int $CreatedBy
     * @param string $title
     * @param $start_event
     * @param $end_event
     * @param string $description
     * @param int $status
     * @param $CreationDate
     * @param $ModificationDate
     */
    public function __construct(int $id, int $CreatedBy, string $title, $start_event, $end_event, string $description, int $status, $CreationDate, $ModificationDate)
    {
        $this->id = $id;
        $this->CreatedBy = $CreatedBy;
        $this->title = $title;
        $this->start_event = $start_event;
        $this->end_event = $end_event;
        $this->description = $description;
        $this->status = $status;
        $this->CreationDate = $CreationDate;
        $this->ModificationDate = $ModificationDate;
    }
}