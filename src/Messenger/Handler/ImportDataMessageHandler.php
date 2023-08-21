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

        $dataPoints = $this->dataImporter->import((string) $device->getName(), $from, $to);

        foreach ($dataPoints as $dataPoint) {
            $metric = new Metric();
            $metric->setPoint(sprintf('POINT(%s %s)', $dataPoint->getLongitude(), $dataPoint->getLatitude()));
            $metric->setCreated($dataPoint->getCreated());
            $metric->setApproval($approval);

            $this->entityManager->persist($metric);
        }

        $approval->setLastImported(new \DateTimeImmutable('now'));
        $approval->setImportState(ImportState::Ready);

        $this->entityManager->persist($approval);
        $this->entityManager->flush();
    }
}
