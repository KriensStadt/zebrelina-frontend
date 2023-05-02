<?php

declare(strict_types=1);

namespace App\Controller\Device;

use App\Entity\Device;
use App\Event\ApprovalChangeEvent;
use App\Form\ApprovalType;
use App\Repository\ApprovalRepository;
use App\Repository\MetricRepository;
use App\Service\TimePeriodProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(path: '/device/{date}', name: 'device.index', defaults: ['date' => null])]
class Index extends AbstractController
{
    public function __construct(
        private readonly TimePeriodProvider $timePeriodProvider,
        private readonly ApprovalRepository $approvalRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MetricRepository $metricRepository,
    ) {
    }

    public function __invoke(Request $request, ?string $date): Response
    {
        /** @var Device $device */
        $device = $this->getUser();

        $timePeriod = $this->timePeriodProvider->getTimePeriod();
        $approval = $this->approvalRepository->findOneByDeviceAndTimePeriod($device, $timePeriod);

        $form = $this->createForm(ApprovalType::class, $approval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($approval);
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new ApprovalChangeEvent($approval));

            $this->entityManager->persist($approval);
            $this->entityManager->flush();

            return $this->redirectToRoute('device.index');
        }

        $availableDates = $this->metricRepository->findAvailableDatesForApproval($approval);
        $filterDate = $this->getFilterDate($date);

        $dataPoints = $this->metricRepository->findDataPointsForApproval($approval, $filterDate);

        return $this->render('/device/index.html.twig', [
            'timePeriod' => $timePeriod,
            'approval' => $approval,
            'form' => $form,
            'dates' => $availableDates,
            'dataPoints' => $dataPoints,
            'filterDate' => $filterDate,
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
