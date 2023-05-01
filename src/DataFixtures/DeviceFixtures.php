<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeviceFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $device = (new Device())
            ->setName('Barry 2')
        ;

        $device->setPassword($this->passwordHasher->hashPassword($device, '1234'));

        $manager->persist($device);
        $manager->flush();
    }
}
