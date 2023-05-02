<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Approval;
use App\Entity\Comment;
use App\Model\DataPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findDataPointsForApproval(Approval $approval): array
    {
        if (!$approval->getId()) {
            return [];
        }

        $query = $this->createQueryBuilder('c')
            ->select([
                'c.createdAt',
                'c.content',
                'ST_X(c.point) AS lon',
                'ST_Y(c.point) AS lat'
            ])

            ->andWhere('c.approval = :approval')
            ->setParameter('approval', $approval)
        ;

        $result = $query
            ->getQuery()
            ->getArrayResult()
        ;

        return array_map(function (array $result): DataPoint {
            /** @var string $lat */
            $lat = $result['lat'];

            /** @var string $lon */
            $lon = $result['lon'];

            /** @var \DateTimeInterface $time */
            $time = $result['createdAt'];

            /** @var string $content */
            $content = $result['content'];

            return new DataPoint(
                latitude: round((float) $lat, 5),
                longitude: round((float) $lon, 5),
                created: $time,
                content: $content,
            );
        }, $result);
    }
}
