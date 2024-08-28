<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\UpdatedAt;
use App\Repository\TimePeriodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimePeriodRepository::class)]
#[ORM\Table(name: 'time_periods')]
class TimePeriod
{
    use Id;
    use CreatedAt;
    use UpdatedAt;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeInterface $periodStart = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeInterface $periodEnd = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $active = true;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $autoClose = true;

    #[ORM\OneToMany(mappedBy: 'timePeriod', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'timePeriod', targetEntity: Approval::class, cascade: ['remove'])]
    private Collection $approvals;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->approvals = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPeriodStart(): ?\DateTimeInterface
    {
        return $this->periodStart;
    }

    public function setPeriodStart(\DateTimeInterface $periodStart): self
    {
        $this->periodStart = $periodStart;

        return $this;
    }

    public function getPeriodEnd(): ?\DateTimeInterface
    {
        return $this->periodEnd;
    }

    public function setPeriodEnd(\DateTimeInterface $periodEnd): self
    {
        $this->periodEnd = $periodEnd;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isAutoClose(): bool
    {
        return $this->autoClose;
    }

    public function setAutoClose(bool $autoClose): self
    {
        $this->autoClose = $autoClose;

        return $this;
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

    public function addApproval(Approval $approval): self
    {
        if (!$this->approvals->contains($approval)) {
            $this->approvals->add($approval);
        }

        return $this;
    }

    public function removeApproval(Approval $approval): self
    {
        $this->approvals->removeElement($approval);

        return $this;
    }

    public function getApprovals(): Collection
    {
        return $this->approvals;
    }
}
