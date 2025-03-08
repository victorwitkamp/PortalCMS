<?php
declare(strict_types=1);


namespace App\Modules\Members;

class MemberPreferences
{
    public ?int $nieuwsbrief;
    public ?int $vrijwilliger;
    public ?int $vrijwilligeroptie1;
    public ?int $vrijwilligeroptie2;
    public ?int $vrijwilligeroptie3;
    public ?int $vrijwilligeroptie4;
    public ?int $vrijwilligeroptie5;

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
