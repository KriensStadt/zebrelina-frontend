<?php

declare(strict_types=1);

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('back_link')]
class BackLink
{
    public string $href;
    public string $text = 'global.back';
}
