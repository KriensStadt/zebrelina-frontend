<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Model\DataImporterInterface;
use App\Model\DataPoint;
use Doctrine\DBAL\Connection;

class RemoteDatabaseDataImporter implements DataImporterInterface
{
    public function __construct(
        private readonly Connection $remoteConnection,
    ) {
    }

    public function import(string $deviceName, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $query = $this->remoteConnection->executeQuery('
            SELECT
                time,
                ST_X(location::geometry) AS lon,
                ST_Y(location::geometry) AS lat
            FROM metrics
            WHERE
                device_id = :device AND
                DATE(time) BETWEEN :from AND :to
            ORDER BY time DESC
        ', [
            'device' => $deviceName,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ]);

        return array_map(function (array $result): DataPoint {
            /** @var string $lat */
            $lat = $result['lat'];

            /** @var string $lon */
            $lon = $result['lon'];

            /** @var string $time */
            $time = $result['time'];

            return new DataPoint(
                latitude: round((float) $lat, 5),
                longitude: round((float) $lon, 5),
                created: new \DateTimeImmutable($time),
            );
        }, $query->fetchAllAssociative());
    }
}
