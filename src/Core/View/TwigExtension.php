<?php

declare(strict_types=1);

namespace PortalCMS\Core\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('text', Text::get(...)),
        ];
    }
}
