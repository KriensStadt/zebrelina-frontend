<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

readonly class MetricRepository
{
    public function __construct(
        private Connection $database
    ) {
    }

    public function findByDeviceId(string $id): array
    {
        $result = $this->database->executeQuery('
            SELECT
                time,
                ST_X(location::geometry) AS long,
                ST_Y(location::geometry) AS lat
            FROM metrics
            WHERE device_id = :device
            ORDER BY time DESC
        ', [
            'device' => $id,
        ]);

        return $result->fetchAllAssociative();
    }
}
