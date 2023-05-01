<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DeviceGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeviceGroupFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $group = (new DeviceGroup())
            ->setName('Group 1')
        ;

        $group->setPassword($this->passwordHasher->hashPassword($group, '1234'));

        $manager->persist($group);
        $manager->flush();
    }
}
