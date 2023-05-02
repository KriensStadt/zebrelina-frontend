<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\UpdatedAt;
use App\Repository\DeviceRepository;
use App\Security\NeedsTimePeriodOnLoginInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ORM\Table(name: 'devices')]
#[UniqueEntity(fields: ['name'])]
class Device implements UserInterface, PasswordAuthenticatedUserInterface, NeedsTimePeriodOnLoginInterface
{
    use Id;
    use CreatedAt;
    use UpdatedAt;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: DeviceGroup::class, mappedBy: 'devices', cascade: ['all'])]
    private Collection $deviceGroups;

    public function __construct()
    {
        $this->deviceGroups = new ArrayCollection();
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

    public function addDeviceGroup(DeviceGroup $deviceGroup): self
    {
        if (!$this->deviceGroups->contains($deviceGroup)) {
            $this->deviceGroups->add($deviceGroup);
        }

        return $this;
    }

    public function removeDeviceGroup(DeviceGroup $deviceGroup): self
    {
        $this->deviceGroups->removeElement($deviceGroup);

        return $this;
    }

    public function getDeviceGroups(): Collection
    {
        return $this->deviceGroups;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->name;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        $roles[] = 'ROLE_DEVICE';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
