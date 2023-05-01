<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DeviceGroup;
use App\Pagination\Paginator;
use App\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeviceGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceGroup[]    findAll()
 * @method DeviceGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceGroupRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorFactory $paginatorFactory,
    ) {
        parent::__construct($registry, DeviceGroup::class);
    }

    public function findAllForAdminOverview(): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->addOrderBy('g.name', 'ASC')
        ;

        return $this->paginatorFactory->create($queryBuilder);
    }
}
