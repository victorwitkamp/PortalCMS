<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ScheduleMemberMailInput
{
    #[Assert\Positive]
    public int $templateid;

    /** @var int[] */
    #[Assert\Count(min: 1)]
    #[Assert\All([ new Assert\Positive() ])]
    public array $recipients;

    /**
     * @param int[] $recipients
     */
    public function __construct(
        int $templateid,
        array $recipients,
    ) {
        $this->templateid = $templateid;
        $this->recipients = array_map('intval', $recipients);
    }
}
