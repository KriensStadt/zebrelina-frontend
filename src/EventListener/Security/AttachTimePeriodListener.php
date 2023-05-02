<?php

declare(strict_types=1);

namespace App\EventListener\Security;

use App\Entity\TimePeriod;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(LoginSuccessEvent::class)]
class AttachTimePeriodListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        /** @var TimePeriod|null $timePeriod */
        $timePeriod = $event->getPassport()->getAttribute('time_period');

        if (!$timePeriod) {
            return;
        }

        $this->requestStack->getSession()->set('time_period', $timePeriod->getId());
    }
}
