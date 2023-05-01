<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Device;
use App\Pagination\Paginator;
use App\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorFactory $paginatorFactory,
    ) {
        parent::__construct($registry, Device::class);
    }

    public function findAllForAdminOverview(): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->addOrderBy('d.name', 'ASC')
        ;

        return $this->paginatorFactory->create($queryBuilder);
    }
}
