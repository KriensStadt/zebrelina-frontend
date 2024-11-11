<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Entity\Metric;
use App\Messenger\Message\ImportDataMessage;
use App\Model\DataImporterInterface;
use App\Model\ImportState;
use App\Repository\ApprovalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportDataMessageHandler
{
    public function __construct(
        private readonly ApprovalRepository $approvalRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DataImporterInterface $dataImporter,
    ) {
    }

    public function __invoke(ImportDataMessage $message): void
    {
        $id = $message->getApprovalId();
        $approval = $this->approvalRepository->find($id);

        if (!$approval) {
            return;
        }

        $device = $approval->getDevice();
        $timePeriod = $approval->getTimePeriod();

        if (!$device || !$timePeriod) {
            return;
        }

        /** @var \DateTimeInterface $from */
        $from = max($approval->getLastImported(), $timePeriod->getPeriodStart());

        /** @var \DateTimeInterface $to */
        $to = $timePeriod->getPeriodEnd();

        // If this approval was imported after the period ends, do nothing
        // Since the metrics are append only, there is nothing to import anymore.
        if ($from > $to) {
            $approval->setImportState(ImportState::Ready);

            $this->entityManager->persist($approval);
            $this->entityManager->flush();

            return;
        }

        $dayRanges = $this->getRangesForDays($from, $to);
        $allDataPoints = [];

        foreach ($dayRanges as $range) {
            [$start, $end] = $range;

            if (false === $start || false === $end) {
                continue;
            }

            $dataPoints = $this->dataImporter->import((string) $device->getName(), $start, $end);

            $allDataPoints = [...$allDataPoints, ...$dataPoints];
        }

        foreach ($allDataPoints as $dataPoint) {
            $metric = new Metric();
            $metric->setPoint(sprintf('POINT(%s %s)', $dataPoint->getLongitude(), $dataPoint->getLatitude()));
            $metric->setCreated($dataPoint->getCreated());
            $metric->setApproval($approval);

            $this->entityManager->persist($metric);
        }

        $approval->setLastImported(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Zurich')));
        $approval->setImportState(ImportState::Ready);

        $this->entityManager->persist($approval);
        $this->entityManager->flush();
    }

    private function getRangesForDays(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $interval = new \DateInterval('P1D');
        /** @var \DateTime $end */
        $end = \DateTime::createFromFormat('Y-m-d', $to->format('Y-m-d'));
        $period = new \DatePeriod($from, $interval, $end->modify('+1 day'));

        $days = [];
        foreach ($period as $date) {
            $days[] = $date;
        }

        $ranges = [];

        foreach ($days as $day) {
            $dayOfWeek = $day->format('w');

            // do not include weekends
            if ($dayOfWeek === '0' || $dayOfWeek === '6') {
                continue;
            }

            // wednesday
            if ($dayOfWeek === '3') {
                $ranges[] = $this->getRange($day, '07:30', '08:30');
                $ranges[] = $this->getRange($day, '11:30', '12:30');
                continue;
            }

            $ranges[] = $this->getRange($day, '07:30', '08:30');
            $ranges[] = $this->getRange($day, '11:30', '14:15');
            $ranges[] = $this->getRange($day, '14:45', '16:45');
        }

        return $ranges;
    }

    private function getRange(\DateTimeInterface $day, string $timeStart, string $timeEnd): array
    {
        return [
            \DateTimeImmutable::createFromFormat('Y-m-d H:i', sprintf('%s %s',
                $day->format('Y-m-d'),
                $timeStart
            )),
            \DateTimeImmutable::createFromFormat('Y-m-d H:i', sprintf('%s %s',
                $day->format('Y-m-d'),
                $timeEnd
            ))
        ];
    }
}
