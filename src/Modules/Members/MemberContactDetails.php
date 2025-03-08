<?php
declare(strict_types=1);


namespace App\Modules\Members;

/**
 * Class MemberContactDetails
 * @package PortalCMS\Modules\Members
 */
class MemberContactDetails
{
    public ?string $telefoon_vast;
    public ?string $telefoon_mobiel;
    public ?string $emailadres;

    public function __construct(string $telefoon_vast = null, string $telefoon_mobiel = null, string $emailadres = null)
    {
        $this->telefoon_vast = $telefoon_vast;
        $this->telefoon_mobiel = $telefoon_mobiel;
        $this->emailadres = $emailadres;
    }
}
