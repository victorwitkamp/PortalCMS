<?php

declare(strict_types=1);

namespace PortalCMS\Features\Pages\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class PageInput
{
    public function __construct(
        #[Assert\NotBlank]
        public string $content,
    ) {
    }
}
