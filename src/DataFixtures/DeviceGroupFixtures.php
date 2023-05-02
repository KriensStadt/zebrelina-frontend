<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\DeviceGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeviceGroupFixtures extends Fixture implements DependentFixtureInterface
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

        for ($i = 0; $i < 3; $i++) {
            /** @var Device $device */
            $device = $this->getReference('device_' . $i);

            $group->addDevice($device);
        }

        $group->setPassword($this->passwordHasher->hashPassword($group, '1234'));

        $manager->persist($group);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DeviceFixtures::class
        ];
    }
}
