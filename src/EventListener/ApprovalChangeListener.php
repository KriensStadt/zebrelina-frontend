<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Approval;
use App\Event\ApprovalChangeEvent;
use App\Messenger\Message\ImportDataMessage;
use App\Model\ImportState;
use App\Repository\MetricRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: ApprovalChangeEvent::class)]
class ApprovalChangeListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly MetricRepository $metricRepository,
    ) {
    }

    public function __invoke(ApprovalChangeEvent $event): void
    {
        $approval = $event->getApproval();

        match ($approval->isApproved()) {
            true => $this->approve($approval),
            false => $this->revoke($approval),
        };
    }

    private function approve(Approval $approval): void
    {
        $approval->setImportState(ImportState::Importing);

        $this->messageBus->dispatch(new ImportDataMessage($approval));
    }

    private function revoke(Approval $approval): void
    {
        $approval->setImportState(ImportState::NotImported);
        $approval->setLastImported(null);

        $this->metricRepository->createQueryBuilder('m')
            ->delete()
            ->andWhere('m.approval = :approval')
            ->setParameter('approval', $approval)
            ->getQuery()
            ->execute()
        ;
    }
}
