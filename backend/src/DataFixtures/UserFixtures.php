<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\Role;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class UserFixtures extends Fixture
{
    public const NUMBER = 10;

    public function __construct(private UserService $userService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('PAXANDDOS');
        $user->setEmail('pashalitovka@gmail.com');
        $user->setRoles([Role::Admin, Role::User]);
        $user->setPassword($this->userService->hashPassword($user, '123456789'));
        $manager->persist($user);
        $this->addReference('user_0', $user);

        $user = new User();
        $user->setName('Luna');
        $user->setEmail('lunadeepblue@gmail.com');
        $user->setRoles([Role::User]);
        $user->setPassword($this->userService->hashPassword($user, '123456789'));
        $manager->persist($user);
        $this->addReference('user_1', $user);

        $user = new User();
        $user->setName('Deeja');
        $user->setEmail('deeja@stab.com');
        $user->setRoles([Role::Admin]);
        $user->setPassword($this->userService->hashPassword($user, '123456789'));
        $manager->persist($user);
        $this->addReference('user_2', $user);

        $generator = Factory::create();
        for ($i = 3; $i <= self::NUMBER; ++$i) {
            $user = new User();
            $user->setName($generator->name);
            $user->setEmail($generator->email);
            $user->setRoles([Role::User]);
            $user->setPassword($this->userService->hashPassword($user, $generator->password(8)));
            $manager->persist($user);

            $this->addReference("user_$i", $user);
        }

        $manager->flush();
    }
}
