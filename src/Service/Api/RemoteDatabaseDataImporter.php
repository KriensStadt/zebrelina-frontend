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
        $from = \DateTime::createFromInterface($from)->setTimezone(new \DateTimeZone('Europe/Zurich'));
        $to = \DateTime::createFromInterface($to)->setTimezone(new \DateTimeZone('Europe/Zurich'));

        $query = $this->remoteConnection->executeQuery('
            SELECT
                time,
                ST_X(location::geometry) AS lon,
                ST_Y(location::geometry) AS lat
            FROM metrics
            WHERE
                device_id = :device AND
                time BETWEEN :from AND :to
            ORDER BY time DESC
        ', [
            'device' => $deviceName,
            'from' => $from->format('Y-m-d H:i:s'),
            'to' => $to->format('Y-m-d 23:59:59'),
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
                created: new \DateTimeImmutable($time, new \DateTimeZone('Europe/Zurich')),
            );
        }, $query->fetchAllAssociative());
    }
}
