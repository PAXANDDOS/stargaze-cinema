<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findAllWithQueryPaginate(array $params): array
    {
        $qb = $this->createQueryBuilder('movie');
        foreach ($params as $param => $value) {
            if (!$this->getClassMetadata()->hasField($param)) {
                continue;
            }

            if ('string' === $this->getClassMetadata()->getTypeOfField($param)) {
                $qb->andWhere($qb->expr()->like("movie.$param", ':value'))
                    ->setParameter('value', "%$value%");
            } else {
                $qb->andWhere($qb->expr()->eq("movie.$param", ':value'))
                    ->setParameter('value', $value);
            }
        }

        $order = array_key_exists('order', $params) ? $params['order'] : 'created_at';
        $method = array_key_exists('orderMethod', $params) ? $params['orderMethod'] : 'DESC';
        $qb->orderBy("movie.$order", $method);

        $page = array_key_exists('page', $params)
            ? ($params['page'] <= 0 ? 1 : intval($params['page']))
            : 1;
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($qb->getQuery(), true);
        $perPage = 20;
        $totalItems = $paginator->count();
        $pages = ceil($totalItems / $perPage);
        $paginator->getQuery()
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return [
            'paginator' => [
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => $pages,
                'totalItems' => $totalItems,
                'nextPage' => $page >= $pages ? null : $page + 1,
                'prevPage' => 1 === $page ? null : $page - 1,
            ],
            'data' => $paginator->getQuery()->getResult(),
        ];
    }
}
