<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

class MemberPreferences
{
    public $nieuwsbrief;
    public $vrijwilliger;
    public $vrijwilligeroptie1;
    public $vrijwilligeroptie2;
    public $vrijwilligeroptie3;
    public $vrijwilligeroptie4;
    public $vrijwilligeroptie5;

    public function __construct(?int $nieuwsbrief, ?int $vrijwilliger, ?int $vrijwilligeroptie1, ?int $vrijwilligeroptie2, ?int $vrijwilligeroptie3, ?int $vrijwilligeroptie4, ?int $vrijwilligeroptie5)
    {
        $this->nieuwsbrief = $nieuwsbrief;
        $this->vrijwilliger = $vrijwilliger;
        $this->vrijwilligeroptie1 = $vrijwilligeroptie1;
        $this->vrijwilligeroptie2 = $vrijwilligeroptie2;
        $this->vrijwilligeroptie3 = $vrijwilligeroptie3;
        $this->vrijwilligeroptie4 = $vrijwilligeroptie4;
        $this->vrijwilligeroptie5 = $vrijwilligeroptie5;
    }
}
