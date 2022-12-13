<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DeviceRepository;
use App\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{device}', name: 'device')]
class Device extends AbstractController
{
    public function __construct(
        private readonly DeviceRepository $deviceRepository,
        private readonly MetricRepository $metricRepository,
    ) {
    }

    public function __invoke(string $device): Response
    {
        if (!$this->deviceRepository->deviceExists($device)) {
            throw $this->createNotFoundException(sprintf('Device "%s" not found', $device));
        }

        $dataPoints = $this->metricRepository->findByDeviceId($device);

        return $this->render('device.html.twig', [
            'device' => $device,
            'dataPoints' => $dataPoints,
        ]);
    }
}
