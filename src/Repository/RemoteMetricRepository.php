<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

readonly class RemoteMetricRepository
{
    public function __construct(
        private Connection $remoteConnection
    ) {
    }

    public function findByDeviceId(string $id, \DateTimeInterface $date): array
    {
        $result = $this->remoteConnection->executeQuery('
            SELECT
                time,
                ST_X(location::geometry) AS long,
                ST_Y(location::geometry) AS lat
            FROM metrics
            WHERE
                device_id = :device AND
                DATE(time) = :date
            ORDER BY time DESC
        ', [
            'device' => $id,
            'date' => $date->format('Y-m-d'),
        ]);

        return $result->fetchAllAssociative();
    }

    public function findDatesForDeviceId(string $id): array
    {
        $result = $this->remoteConnection->executeQuery('
            SELECT DISTINCT DATE(time) as date
            FROM metrics
            WHERE device_id = :device
            ORDER BY date DESC
        ', [
            'device' => $id,
        ]);

        $results = $result->fetchFirstColumn();

        return array_map(fn (string $date) => new \DateTimeImmutable($date), $results);
    }
}
