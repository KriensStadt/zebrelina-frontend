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
        $start = new \DateTimeImmutable('last monday');
        $end = new \DateTimeImmutable('last monday + 1 year');

        $interval = new \DateInterval('P7D');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $recurrence) {
            $to = \DateTimeImmutable::createFromInterface($recurrence);
            $to = $to->add($interval);

            $timePeriod = (new TimePeriod())
                ->setPeriodStart($recurrence)
                ->setPeriodEnd($to)
                ->setName(uniqid())
                ->setActive(random_int(0, 1) > 0)
            ;

            $manager->persist($timePeriod);
        }

        $manager->flush();
    }
}
