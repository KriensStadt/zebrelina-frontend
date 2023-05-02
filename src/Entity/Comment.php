<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\Point;
use App\Database\Field\UpdatedAt;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    use Id;
    use CreatedAt;
    use UpdatedAt;
    use Point;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: CommentType::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?CommentType $commentType = null;

    #[ORM\ManyToOne(targetEntity: Approval::class, cascade: ['persist'], inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Approval $approval = null;

    #[ORM\ManyToOne(targetEntity: DeviceGroup::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?DeviceGroup $deviceGroup = null;

    #[ORM\ManyToOne(targetEntity: TimePeriod::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?TimePeriod $timePeriod = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getTimePeriod(): ?TimePeriod
    {
        return $this->timePeriod;
    }

    public function setTimePeriod(TimePeriod $timePeriod): self
    {
        $this->timePeriod = $timePeriod;

        return $this;
    }

    public function getDeviceGroup(): ?DeviceGroup
    {
        return $this->deviceGroup;
    }

    public function setDeviceGroup(DeviceGroup $deviceGroup): self
    {
        $this->deviceGroup = $deviceGroup;

        return $this;
    }

    public function getCommentType(): ?CommentType
    {
        return $this->commentType;
    }

    public function setCommentType(CommentType $commentType): self
    {
        $this->commentType = $commentType;

        return $this;
    }
}
