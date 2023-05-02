<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Approval;
use App\Entity\Metric;
use App\Model\DataPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Metric|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metric|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metric[]    findAll()
 * @method Metric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetricRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metric::class);
    }

    public function findDataPointsForApproval(Approval $approval, ?\DateTimeInterface $date = null): array
    {
        if (!$approval->getId()) {
            return [];
        }

        $query = $this->createQueryBuilder('m')
            ->select([
                'm.created',
                'ST_X(m.point) AS lon',
                'ST_Y(m.point) AS lat'
            ])

            ->andWhere('m.approval = :approval')
            ->setParameter('approval', $approval)
        ;

        if (null !== $date) {
            $query
                ->andWhere('DATE(m.created) = DATE(:date)')
                ->setParameter('date', $date)
            ;
        }

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
            $time = $result['created'];

            return new DataPoint(
                latitude: round((float) $lat, 5),
                longitude: round((float) $lon, 5),
                created: $time
            );
        }, $result);
    }

    public function findAvailableDatesForApproval(Approval $approval): array
    {
        if (!$approval->getId()) {
            return [];
        }


        $results = $this->createQueryBuilder('m')
            ->select('DISTINCT DATE(m.created) AS created')
            ->andWhere('m.approval = :approval')
            ->setParameter('approval', $approval)
            ->addOrderBy('created', 'DESC')

            ->getQuery()
            ->execute()
        ;

        return array_map(fn (array $result) => new \DateTimeImmutable($result['created']), $results);
    }
}
