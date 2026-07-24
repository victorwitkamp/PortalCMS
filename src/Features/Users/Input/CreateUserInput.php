<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex('/^[a-zA-Z0-9]{2,64}$/')]
        public string $user_name,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(max: 254)]
        public string $user_email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public string $user_password,
    ) {
    }
}
