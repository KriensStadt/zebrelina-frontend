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
        $names = ['barry2', 'barry3', 'barry4'];

        foreach ($names as $i => $name) {
            $device = (new Device())
                ->setName($name)
            ;

            $device->setPassword($this->passwordHasher->hashPassword($device, '1234'));
            $manager->persist($device);

            $this->addReference('device_' . $i, $device);
        }

        $manager->flush();
    }
}
