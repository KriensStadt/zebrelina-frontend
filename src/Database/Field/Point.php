<?php

declare(strict_types=1);

namespace App\Database\Field;

use Doctrine\ORM\Mapping as ORM;
use Jsor\Doctrine\PostGIS\Types\PostGISType;

trait Point
{
    #[ORM\Column(type: PostGISType::GEOMETRY, nullable: false, options: ['geometry_type' => 'POINT'])]
    private ?string $point = null;

    public function getPoint(): ?string
    {
        return $this->point;
    }

    public function setPoint(string $point): self
    {
        $this->point = $point;

        return $this;
    }
}
