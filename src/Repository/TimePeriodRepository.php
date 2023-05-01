<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TimePeriod;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimePeriod::class);
    }

    public function findOneByName(string $name): ?TimePeriod
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $name)

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
