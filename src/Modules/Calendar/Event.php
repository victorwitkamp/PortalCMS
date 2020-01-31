<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

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
     * @param string $start_event
     * @param string $end_event
     * @param string $description
     * @param int $status
     * @param string $CreationDate
     * @param string $ModificationDate
     */
    public function __construct(int $id, int $CreatedBy, string $title, string $start_event, string $end_event, string $description, int $status, string $CreationDate, string $ModificationDate)
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
