<?php

declare(strict_types=1);

namespace App\Controller\Admin\TimePeriod;

use App\Entity\Approval;
use App\Entity\TimePeriod;
use App\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/time-period/{timePeriod}/export',
    name: 'admin.time_period.export',
)]
class Export extends AbstractController
{
    public function __construct(
        private readonly MetricRepository $repository,
    ) {
    }

    public function __invoke(Request $request, TimePeriod $timePeriod): Response
    {
        $showExport = $timePeriod->getPeriodEnd() < new \DateTime() && !$timePeriod->isActive();

        if (!$showExport) {
            return new JsonResponse([]);
        }

        $data = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        /** @var Approval $approval */
        foreach ($timePeriod->getApprovals() as $approval) {
            $deviceName = $approval->getDevice()?->getName() ?? 'no Name';
            $dataPoints = $this->repository->findDataPointsForApproval($approval);
            $coordinates = [];

            foreach ($dataPoints as $dataPoint) {
                $coordinates[] = [$dataPoint->getLatitude(), $dataPoint->getLongitude()];
            }

            $data['features'][] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'LineString',
                    'coordinates' => $coordinates,
                ],
                'properties' => [
                    'tracker' => $deviceName,
                ]
            ];
        }

        $jsonContent = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $fileName = sprintf('%s.geojson', $timePeriod->getName() ?? 'export');

        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $fileName));

        return $response;
    }
}
