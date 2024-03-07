<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('html_entity_decode', [$this, 'htmlEntityDecode']),
            new TwigFilter('substr', [$this, 'substr']),
        ];
    }

    public function htmlEntityDecode(string $value): string
    {
        return html_entity_decode($value);
    }

    public function substr(string $value): string
    {
        return substr($value,0, 60);
    }
}
