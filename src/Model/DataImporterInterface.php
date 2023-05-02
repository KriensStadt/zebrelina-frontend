<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\Api\DataPoint;

interface DataImporterInterface
{
    /**
     * @return array<DataPoint>
     */
    public function import(string $deviceName, \DateTimeInterface $from, \DateTimeInterface $to): array;
}
