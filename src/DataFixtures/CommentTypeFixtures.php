<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CommentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = ['Unübersichtliche Strassenkreuzung', 'Fehlendes Lichtsignal'];

        foreach ($names as $name) {
            $commentType = new CommentType();
            $commentType->setName($name);

            $manager->persist($commentType);
        }

        $manager->flush();
    }
}
