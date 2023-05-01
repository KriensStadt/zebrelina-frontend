<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RemoteDeviceRepository;
use App\Repository\RemoteMetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/asdf/{device}/{dateString}', name: 'device', defaults: ['dateString' => null])]
class Device extends AbstractController
{
    public function __construct(
        private readonly RemoteDeviceRepository $deviceRepository,
        private readonly RemoteMetricRepository $metricRepository,
    ) {
    }

    public function __invoke(string $device, ?string $dateString): Response
    {
        if (!$this->deviceRepository->deviceExists($device)) {
            throw $this->createNotFoundException(sprintf('Device "%s" not found', $device));
        }

        $date = null;
        $dataPoints = [];
        $dates = $this->metricRepository->findDatesForDeviceId($device);

        if (\count($dates) > 0) {
            // Use the first available date, if none given
            $date = $dates[0];

            if (null !== $dateString) {
                try {
                    $date = new \DateTimeImmutable($dateString);
                } catch (\Exception) {
                }
            }

            $dataPoints = $this->metricRepository->findByDeviceId($device, $date);
        }

        return $this->render('device.html.twig', [
            'device' => $device,
            'dataPoints' => $dataPoints,
            'dates' => $dates,
            'currentDate' => $date,
        ]);
    }
}
