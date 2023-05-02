<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\UpdatedAt;
use App\Repository\DeviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ORM\Table(name: 'approvals')]
class Approval
{
    use Id;
    use CreatedAt;
    use UpdatedAt;

    #[ORM\ManyToOne(targetEntity: TimePeriod::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimePeriod $timePeriod = null;

    #[ORM\ManyToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $approved = false;

    public function getTimePeriod(): ?TimePeriod
    {
        return $this->timePeriod;
    }

    public function setTimePeriod(TimePeriod $timePeriod): self
    {
        $this->timePeriod = $timePeriod;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(Device $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }
}
