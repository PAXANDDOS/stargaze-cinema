<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public const NUMBER = 30;

    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create();

        for ($i = 0; $i <= self::NUMBER; ++$i) {
            $session = $this->getReference('session_'.$generator->numberBetween(0, SessionFixtures::NUMBER));

            $ticket = new Ticket();
            $ticket->setPlace($generator->numberBetween(1, $session->getHall()->getCapacity()));
            $ticket->setUser($this->getReference('user_'.$generator->numberBetween(0, UserFixtures::NUMBER)));
            $ticket->setSession($session);

            $manager->persist($ticket);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SessionFixtures::class,
        ];
    }
}
