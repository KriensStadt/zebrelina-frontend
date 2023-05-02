<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\Id;
use App\Repository\MetricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Jsor\Doctrine\PostGIS\Types\PostGISType;

#[ORM\Entity(repositoryClass: MetricRepository::class)]
#[ORM\Table(name: 'metrics')]
class Metric
{
    use Id;

    #[ORM\Column(type: PostGISType::GEOMETRY, nullable: false, options: ['geometry_type' => 'POINT'])]
    private ?string $point = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(targetEntity: Approval::class, cascade: ['persist'], inversedBy: 'metrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Approval $approval = null;

    public function getPoint(): ?string
    {
        return $this->point;
    }

    public function setPoint(string $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getApproval(): ?Approval
    {
        return $this->approval;
    }

    public function setApproval(Approval $approval): self
    {
        $this->approval = $approval;

        return $this;
    }
}
