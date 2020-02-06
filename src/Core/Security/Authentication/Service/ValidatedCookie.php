<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace PortalCMS\Core\Security\Authentication\Service;

class ValidatedCookie
{
    public $user_id;
    public $token;

    public function __construct(int $user_id, string $token)
    {
        $this->user_id = $user_id;
        $this->token = $token;
        //return $this;
    }
}
