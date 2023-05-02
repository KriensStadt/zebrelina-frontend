<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TimePeriod;
use App\Repository\TimePeriodRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TimePeriodProvider
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TimePeriodRepository $timePeriodRepository,
    ) {
    }

    public function getTimePeriod(): TimePeriod
    {
        $session = $this->requestStack->getSession();

        if (!$session->has('time_period')) {
            throw new \InvalidArgumentException('No time period found on this user');
        }

        $id = $session->get('time_period');
        $timePeriod = $this->timePeriodRepository->find($id);

        if (!$timePeriod instanceof TimePeriod) {
            throw new \InvalidArgumentException('Time period is not an object of type App\\Entity\\TimePeriod');
        }

        return $timePeriod;
    }
}
