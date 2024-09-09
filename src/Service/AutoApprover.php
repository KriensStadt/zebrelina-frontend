<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Approval;
use App\Entity\Device;
use App\Entity\TimePeriod;
use App\Repository\DeviceRepository;
use App\Repository\TimePeriodRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class AutoApprover
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TimePeriodRepository $timePeriodRepository,
        private DeviceRepository $deviceRepository,
    ) {
    }

    public function createApprovalsForDevice(Device $device): void
    {
        $timePeriods = $this->timePeriodRepository->findAll();

        foreach ($timePeriods as $timePeriod) {
            $approval = (new Approval())
                ->setDevice($device)
                ->setTimePeriod($timePeriod);

            $this->entityManager->persist($approval);
        }
    }

    public function createApprovalsForTimePeriod(TimePeriod $timePeriod): void
    {
        $devices = $this->deviceRepository->findAll();

        foreach ($devices as $device) {
            $approval = (new Approval())
                ->setDevice($device)
                ->setTimePeriod($timePeriod);

            $this->entityManager->persist($approval);
        }
    }
}
