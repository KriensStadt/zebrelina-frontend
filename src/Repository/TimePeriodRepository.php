<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TimePeriod;
use App\Pagination\Paginator;
use App\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TimePeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimePeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimePeriod[]    findAll()
 * @method TimePeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimePeriodRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorFactory $paginatorFactory,
    ) {
        parent::__construct($registry, TimePeriod::class);
    }

    public function findOneActiveByName(string $name): ?TimePeriod
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->andWhere('p.active = true')
            ->setParameter('name', $name)

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllForAdminOverview(): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->addOrderBy('t.periodStart', 'DESC')
        ;

        return $this->paginatorFactory->create($queryBuilder);
    }
}
