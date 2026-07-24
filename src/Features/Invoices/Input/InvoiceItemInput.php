<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class InvoiceItemInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public string $name,
        #[Assert\PositiveOrZero]
        public int $price,
    ) {
    }
}
