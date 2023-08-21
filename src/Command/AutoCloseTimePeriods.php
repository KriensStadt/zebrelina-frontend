<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TimePeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'zebrina:auto-close-time-periods')]
class AutoCloseTimePeriods extends Command
{
    public function __construct(
        private readonly TimePeriodRepository $timePeriodRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Zurich'));

        $timePeriods = $this->timePeriodRepository->findAutoClosable($now);

        foreach ($timePeriods as $timePeriod) {
            $timePeriod->setActive(false);

            $this->entityManager->persist($timePeriod);
        }

        $this->entityManager->flush();

        $io->success(sprintf('Closed %d time periods', \count($timePeriods)));

        return Command::SUCCESS;
    }
}
