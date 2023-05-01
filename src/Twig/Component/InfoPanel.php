<?php

declare(strict_types=1);

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('info_panel')]
class InfoPanel
{
    public string $type = 'info';
    public ?string $title = null;
    public string $text;

    #[ExposeInTemplate]
    public function getColor(): string
    {
        return match ($this->type) {
            'info' => 'blue',
            'warning' => 'yellow',
            'danger' => 'red',
            'success' => 'green',

            default => throw new \InvalidArgumentException(sprintf('Type %s is not supported', $this->type)),
        };
    }
}
