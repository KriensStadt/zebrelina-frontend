<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\UpdatedAt;
use App\Model\ImportState;
use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::INTEGER, enumType: ImportState::class)]
    private ImportState $importState = ImportState::NotImported;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastImported = null;

    #[ORM\OneToMany(mappedBy: 'approval', targetEntity: Metric::class, cascade: ['remove'])]
    private Collection $metrics;

    #[ORM\OneToMany(mappedBy: 'approval', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comments;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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

    public function getImportState(): ImportState
    {
        return $this->importState;
    }

    public function setImportState(ImportState $importState): self
    {
        $this->importState = $importState;

        return $this;
    }

    public function getLastImported(): ?\DateTimeInterface
    {
        return $this->lastImported;
    }

    public function setLastImported(?\DateTimeInterface $lastImported): self
    {
        $this->lastImported = $lastImported;

        return $this;
    }

    public function addMetric(Metric $metric): self
    {
        if (!$this->metrics->contains($metric)) {
            $this->metrics->add($metric);
        }

        return $this;
    }

    public function removeMetric(Metric $metric): self
    {
        $this->metrics->removeElement($metric);

        return $this;
    }

    public function getMetrics(): Collection
    {
        return $this->metrics;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
