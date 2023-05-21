<?php

declare(strict_types=1);

namespace Infrastructure\Shared\Symfony\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class IconExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'icon'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('icon', [$this, 'icon'], ['is_safe' => ['html']]),
        ];
    }

    public function icon(string $name): string
    {
        return <<< HTML
            <em class="icon ni ni-{$name}" aria-label="icon {$name}" role="img"></em>
        HTML;
    }
}
