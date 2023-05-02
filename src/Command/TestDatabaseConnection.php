<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'zebrina:test-database-connection')]
class TestDatabaseConnection extends Command
{
    public function __construct(
        private readonly Connection $defaultConnection,
        private readonly Connection $remoteConnection
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->defaultConnection->executeQuery('SELECT 1');

            $io->success('Successfully connected to "default" dbal connection');
        } catch (Exception) {
            $io->error('Connecting to "default" dbal connection failed');
        }

        try {
            $this->remoteConnection->executeQuery('SELECT 1');

            $io->success('Successfully connected to "remote" dbal connection');
        } catch (Exception) {
            $io->error('Connecting to "remote" dbal connection failed');
        }

        return Command::SUCCESS;
    }
}
