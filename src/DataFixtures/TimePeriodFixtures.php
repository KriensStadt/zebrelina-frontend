<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TimePeriod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TimePeriodFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $start = new \DateTimeImmutable('2022-06-23', new \DateTimeZone('Europe/Zurich'));
        $end = new \DateTimeImmutable('2022-07-01', new \DateTimeZone('Europe/Zurich'));

        $timePeriod = (new TimePeriod())
            ->setPeriodStart($start)
            ->setPeriodEnd($end)
            ->setName('barry2')
            ->setActive(true)
            ->setAutoClose(false)
        ;

        $manager->persist($timePeriod);
        $manager->flush();
    }
}
