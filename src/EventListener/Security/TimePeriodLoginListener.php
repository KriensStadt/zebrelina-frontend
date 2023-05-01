<?php

declare(strict_types=1);

namespace App\EventListener\Security;

use App\Repository\TimePeriodRepository;
use App\Security\NeedsTimePeriodOnLoginInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

#[AsEventListener(CheckPassportEvent::class)]
class TimePeriodLoginListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TimePeriodRepository $timePeriodRepository,
    ) {
    }

    public function __invoke(CheckPassportEvent $event): void
    {
        $user = $event->getPassport()->getUser();

        if (!$user instanceof NeedsTimePeriodOnLoginInterface) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $timePeriodName = (string) $request->request->get('_time_period');

        if (!$timePeriodName) {
            throw new CustomUserMessageAuthenticationException('No time period password provided');
        }

        $timePeriod = $this->timePeriodRepository->findOneActiveByName($timePeriodName);

        if (!$timePeriod) {
            throw new CustomUserMessageAuthenticationException('Time period not found');
        }

        $event->getPassport()->setAttribute('time_period', $timePeriod);
    }
}
