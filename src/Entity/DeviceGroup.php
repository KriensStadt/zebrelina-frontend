<?php

declare(strict_types=1);

namespace App\Entity;

use App\Database\Field\CreatedAt;
use App\Database\Field\Id;
use App\Database\Field\UpdatedAt;
use App\Repository\DeviceGroupRepository;
use App\Security\NeedsTimePeriodOnLoginInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceGroupRepository::class)]
#[ORM\Table(name: 'device_groups')]
#[UniqueEntity(fields: ['name'])]
class DeviceGroup implements UserInterface, PasswordAuthenticatedUserInterface, NeedsTimePeriodOnLoginInterface
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->name;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        $roles[] = 'ROLE_GROUP';

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
