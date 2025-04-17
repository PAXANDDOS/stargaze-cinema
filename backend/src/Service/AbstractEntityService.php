<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractEntityService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    final protected function getEntityRepository(string $className): \Doctrine\Persistence\ObjectRepository
    {
        return $this->entityManager->getRepository($className);
    }

    final public function save(AbstractEntity $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    final public function delete(AbstractEntity $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
