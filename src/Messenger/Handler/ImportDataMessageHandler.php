<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\ImportDataMessage;
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
    ) {
    }

    public function __invoke(ImportDataMessage $message): void
    {
        $id = $message->getApprovalId();
        $approval = $this->approvalRepository->find($id);

        if (!$approval) {
            return;
        }

        // Perform actual import
        // ...

        $approval->setLastImported(new \DateTimeImmutable('now'));
        $approval->setImportState(ImportState::Ready);

        $this->entityManager->persist($approval);
        $this->entityManager->flush();
    }
}
