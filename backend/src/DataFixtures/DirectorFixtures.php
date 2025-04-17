<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Director;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class DirectorFixtures extends Fixture
{
    public const NUMBER = 30;

    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create();

        $director = new Director();
        $director->setName('Don Hall');
        $manager->persist($director);
        $this->addReference('director_0', $director);

        for ($i = 1; $i <= self::NUMBER; ++$i) {
            $director = new Director();
            $director->setName($generator->name);
            $manager->persist($director);

            $this->addReference("director_$i", $director);
        }

        $manager->flush();
    }
}
