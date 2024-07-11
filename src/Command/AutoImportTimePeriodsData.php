<?php

declare(strict_types=1);

namespace App\Command;

use App\Messenger\Message\ImportDataMessage;
use App\Repository\ApprovalRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'zebrelina:auto-import-time-periods-data')]
class AutoImportTimePeriodsData extends Command
{
    public function __construct(
        private readonly ApprovalRepository $approvalRepository,
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Zurich'));

        $approvals = $this->approvalRepository->findByActiveTimePeriods($now);

        foreach ($approvals as $approval) {
            $this->messageBus->dispatch(new ImportDataMessage($approval));
        }

        $io->success(sprintf('Dispatched Import Message for %d approvals', \count($approvals)));

        return Command::SUCCESS;
    }
}
