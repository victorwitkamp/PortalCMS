<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Modules\Members;

/**
 * Class MemberContactDetails
 * @package PortalCMS\Modules\Members
 */
class MemberContactDetails
{
    public $telefoon_vast;
    public $telefoon_mobiel;
    public $emailadres;

    public function __construct(string $telefoon_vast = null, string $telefoon_mobiel = null, string $emailadres = null)
    {
        $this->telefoon_vast = $telefoon_vast;
        $this->telefoon_mobiel = $telefoon_mobiel;
        $this->emailadres = $emailadres;
    }
}
