<?php

declare(strict_types=1);

namespace PortalCMS\Features\Events\Input;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class EventInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,
        #[Assert\NotNull]
        public DateTimeImmutable $start_event,
        #[Assert\NotNull]
        public DateTimeImmutable $end_event,
        #[Assert\Length(max: 65535)]
        public ?string $description = null,
        #[Assert\Range(min: 0, max: 2)]
        public int $status = 0,
    ) {
    }
}
