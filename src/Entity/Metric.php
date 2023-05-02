<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\Id;
use App\Database\Field\Point;
use App\Repository\MetricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetricRepository::class)]
#[ORM\Table(name: 'metrics')]
class Metric
{
    use Id;
    use Point;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(targetEntity: Approval::class, cascade: ['persist'], inversedBy: 'metrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Approval $approval = null;

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
