<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Metric;
use App\Repository\ApprovalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'zebrelina:auto-delete-unapproved-metrics')]
class AutoDeleteUnapprovedMetrics extends Command
{
    public function __construct(
        private readonly ApprovalRepository $approvalRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // offset
        $now = new \DateTimeImmutable('now + 7 days', new \DateTimeZone('Europe/Zurich'));

        $approvals = $this->approvalRepository->findAutoDeletable($now);

        foreach ($approvals as $approval) {
            /** @var Metric $metric */
            foreach ($approval->getMetrics() as $metric) {
                $this->entityManager->remove($metric);
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('Deleted unapproved metrics for %d approvals', \count($approvals)));

        return Command::SUCCESS;
    }
}
