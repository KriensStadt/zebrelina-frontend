<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LogoExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public')]
        private readonly string $publicDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('hasPlatformLogo', [$this, 'hasPlatformLogo']),
            new TwigFunction('getPlatformLogoPath', [$this, 'getPlatformLogoPath']),
        ];
    }

    public function hasPlatformLogo(): bool
    {
        return file_exists(sprintf('%s/%s', rtrim($this->publicDir,'/'), ltrim($this->getPlatformLogoPath(), '/')));
    }

    public function getPlatformLogoPath(): string
    {
        return '/logo.png';
    }
}
