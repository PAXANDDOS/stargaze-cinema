<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class GenreFixtures extends Fixture
{
    public const NUMBER = 10;

    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create();

        $genre = new Genre();
        $genre->setName('Animation');
        $manager->persist($genre);
        $this->addReference('genre_0', $genre);

        for ($i = 1; $i <= self::NUMBER; ++$i) {
            $genre = new Genre();
            $genre->setName($generator->colorName);
            $manager->persist($genre);

            $this->addReference("genre_$i", $genre);
        }

        $manager->flush();
    }
}
