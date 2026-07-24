<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Features\Email\Recipient;

final readonly class EmailRecipient
{
    public function __construct(
        public string $email,
        public ?string $name = null,
    ) {
    }
}
