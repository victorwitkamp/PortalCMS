<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class MailTemplateInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $subject,
        #[Assert\NotBlank]
        public string $body,
    ) {
    }
}
