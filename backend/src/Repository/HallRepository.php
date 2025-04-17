<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Hall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hall|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hall|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hall[]    findAll()
 * @method Hall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hall::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hall $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Hall $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
