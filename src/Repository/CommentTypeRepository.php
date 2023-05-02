<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CommentType;
use App\Pagination\Paginator;
use App\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentType[]    findAll()
 * @method CommentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTypeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorFactory $paginatorFactory,
    ) {
        parent::__construct($registry, CommentType::class);
    }

    public function findAllForAdminOverview(): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->addOrderBy('c.name', 'ASC')
        ;

        return $this->paginatorFactory->create($queryBuilder);
    }
}
