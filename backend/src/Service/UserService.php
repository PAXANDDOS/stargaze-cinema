<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Enum\Role;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService extends AbstractEntityService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        \Doctrine\ORM\EntityManagerInterface $entityManager
    ) {
        parent::__construct($entityManager);
    }

    public function create(array $params, User $user = new User()): User
    {
        if (isset($params['name'])) {
            $user->setName($params['name']);
        }
        if (isset($params['email'])) {
            $user->setEmail($params['email']);
        }
        if (isset($params['password'])) {
            $user->setPassword($this->hashPassword($user, $params['password']));
        }
        if (!isset($params['passwordConfirmation'])) {
            if ($roles = $params['roles']) {
                $user->setRoles($roles);
            }
        } else {
            $user->setRoles([Role::User]);
        }

        return $user;
    }

    final public function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}
