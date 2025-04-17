<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUMBER = 60;

    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create();

        for ($i = 0; $i <= self::NUMBER; ++$i) {
            $session = new Session();
            $session->setBeginAt($generator->dateTimeBetween('now', 'now'));
            $session->setEndAt($generator->dateTimeBetween('+4 hours', '+4 hours'));
            $session->setMovie($this->getReference('movie_'.$generator->numberBetween(0, MovieFixtures::NUMBER)));
            $session->setHall($this->getReference('hall_'.$generator->numberBetween(0, HallFixtures::NUMBER)));
            $manager->persist($session);

            $this->addReference("session_$i", $session);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
            HallFixtures::class,
        ];
    }
}
