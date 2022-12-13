<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

readonly class DeviceRepository
{
    public function __construct(
        private Connection $database
    ) {
    }

    public function getDeviceIds(): array
    {
        return $this->database
            ->executeQuery('SELECT DISTINCT device_id FROM metrics ORDER BY device_id')
            ->fetchFirstColumn();
    }

    public function deviceExists(string $id): bool
    {
        $deviceExists = $this->database->executeQuery('SELECT 1 FROM metrics WHERE device_id = :device LIMIT 1', [
            'device' => $id,
        ]);

        return $deviceExists->fetchOne() !== false;
    }
}
