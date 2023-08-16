<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\LocaleSwitcher;

#[AsEventListener(event: KernelEvents::REQUEST)]
readonly class LocaleListener
{
    public function __construct(
        private LocaleSwitcher $localeSwitcher,

        #[Autowire('%kernel.default_locale%')]
        private string $defaultLocale,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($locale = $request->query->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        }

        /** @var string $locale */
        $locale = $request->getSession()->get('_locale', $this->defaultLocale);

        $this->localeSwitcher->setLocale($locale);
    }
}
