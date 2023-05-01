<?php

declare(strict_types=1);

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button')]
class Button
{
    public string $label;
    public ?string $href = null;

    public string $type = 'default';
    public bool $stretch = false;
    public bool $disabled = false;
}
