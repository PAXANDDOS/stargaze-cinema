<?php

namespace App\Repository;

use App\Entity\Frame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Frame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frame[]    findAll()
 * @method Frame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Frame::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Frame $entity, bool $flush = true): void
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
    public function remove(Frame $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
