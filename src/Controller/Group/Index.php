<?php

declare(strict_types=1);

namespace App\Controller\Group;

use App\Entity\DeviceGroup;
use App\Repository\CommentRepository;
use App\Repository\MetricRepository;
use App\Service\PolylineGenerator;
use App\Service\TimePeriodProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group/{date}', name: 'group.index', requirements: ['date' => '\d+-\d+-\d+'], defaults: ['date' => null])]
class Index extends AbstractController
{
    public function __construct(
        private readonly TimePeriodProvider $timePeriodProvider,
        private readonly MetricRepository $metricRepository,
        private readonly CommentRepository $commentRepository,
        private readonly PolylineGenerator $polylineGenerator,
    ) {
    }

    public function __invoke(?string $date): Response
    {
        /** @var DeviceGroup $group */
        $group = $this->getUser();

        $timePeriod = $this->timePeriodProvider->getTimePeriod();

        $availableDates = $this->metricRepository->findAvailableDatesForGroupAndTimePeriod($group, $timePeriod);
        $filterDate = $this->getFilterDate($date);

        $dataPoints = $this->metricRepository->findDataPointsForGroupAndTimePeriod($group, $timePeriod, $filterDate);
        $commentPoints = $this->commentRepository->findDataPointsForGroupAndTimePeriod($group, $timePeriod);
        $groupCommentPoints = $this->commentRepository->findGroupDataPointsForGroupAndTimePeriod($group, $timePeriod);
        $polylines = $this->polylineGenerator->createLines($dataPoints);

        return $this->render('/group/index.html.twig', [
            'date' => $date,
            'timePeriod' => $timePeriod,
            'dates' => $availableDates,
            'filterDate' => $filterDate,
            'dataPoints' => $dataPoints,
            'commentPoints' => $commentPoints,
            'groupCommentPoints' => $groupCommentPoints,
            'polylines' => $polylines,
        ]);
    }

    private function getFilterDate(?string $dateString): ?\DateTimeInterface
    {
        if (null === $dateString) {
            return null;
        }

        try {
            return new \DateTimeImmutable($dateString);
        } catch (\Exception) {
        }

        return null;
    }
}
