<?php


declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2019.
 */

namespace App\Core\Security\Authentication\Service;

class ValidatedCookie
{
    public int $user_id;
    public string $token;

    public function __construct(int $user_id, string $token)
    {
        $this->user_id = $user_id;
        $this->token = $token;
        //return $this;
    }
}
