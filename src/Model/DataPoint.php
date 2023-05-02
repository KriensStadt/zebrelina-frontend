<?php

declare(strict_types=1);

namespace App\Model;

class DataPoint
{
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly \DateTimeInterface $created,
    ) {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }
}
