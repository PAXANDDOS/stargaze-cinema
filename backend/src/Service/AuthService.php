<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Enum\Role;
use App\Exception\UnauthorizedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthService
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * @throws UnauthorizedException
     */
    private function getUserToken(): ?TokenInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            throw new UnauthorizedException();
        }

        return $token;
    }

    public function getCurrentUser(): User
    {
        return $this->getUserToken()->getUser();
    }

    public function authenticatedAsAdmin(): bool
    {
        $roles = $this->getCurrentUser()->getRoles();
        if (!in_array(Role::Admin, $roles)) {
            return false;
        }

        return true;
    }

    public function authenticatedAsModer(): bool
    {
        $roles = $this->getCurrentUser()->getRoles();
        if (!in_array(Role::Moder, $roles)) {
            return false;
        }

        return true;
    }

    public function isAuthenticated(): bool
    {
        return (bool) $this->tokenStorage->getToken();
    }
}
