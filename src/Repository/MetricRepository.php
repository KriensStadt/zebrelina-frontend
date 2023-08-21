<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Approval;
use App\Entity\DeviceGroup;
use App\Entity\Metric;
use App\Entity\TimePeriod;
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
                'ST_Y(m.point) AS lat',
                'd.name AS grp'
            ])

            ->leftJoin('m.approval', 'a')
            ->leftJoin('a.device', 'd')

            ->andWhere('a = :approval')

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

        return $this->mapToDataPoints($result);
    }

    public function findDataPointsForGroupAndTimePeriod(DeviceGroup $deviceGroup, TimePeriod $timePeriod, ?\DateTimeInterface $date = null): array
    {
        $query = $this->createQueryBuilder('m')
            ->select([
                'm.created',
                'ST_X(m.point) AS lon',
                'ST_Y(m.point) AS lat',
                'CONCAT(g.name, d.name) as grp',
            ])

            ->leftJoin('m.approval', 'a')
            ->leftJoin('a.device', 'd')
            ->leftJoin('d.deviceGroups', 'g')

            ->andWhere('a.timePeriod = :timePeriod')
            ->andWhere('a.approved = true')
            ->andWhere('g = :group')

            ->addOrderBy('grp')
            ->addOrderBy('m.created')

            ->setParameter('timePeriod', $timePeriod)
            ->setParameter('group', $deviceGroup)
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

        return $this->mapToDataPoints($result);
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

        return array_map(fn (array $result) => new \DateTimeImmutable($result['created'], new \DateTimeZone('Europe/Zurich')), $results);
    }

    public function findAvailableDatesForGroupAndTimePeriod(DeviceGroup $deviceGroup, TimePeriod $timePeriod): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('DISTINCT DATE(m.created) AS created')

            ->leftJoin('m.approval', 'a')
            ->leftJoin('a.device', 'd')
            ->leftJoin('d.deviceGroups', 'g')

            ->andWhere('a.timePeriod = :timePeriod')
            ->andWhere('a.approved = true')
            ->andWhere('g = :group')

            ->addOrderBy('created', 'DESC')

            ->setParameter('timePeriod', $timePeriod)
            ->setParameter('group', $deviceGroup)

            ->getQuery()
            ->execute()
        ;

        return array_map(fn (array $result) => new \DateTimeImmutable($result['created'], new \DateTimeZone('Europe/Zurich')), $results);
    }

    /**
     * @return array<DataPoint>
     */
    private function mapToDataPoints(array $result): array
    {
        return array_map(function (array $result): DataPoint {
            /** @var string $lat */
            $lat = $result['lat'];

            /** @var string $lon */
            $lon = $result['lon'];

            /** @var \DateTimeInterface $time */
            $time = $result['created'];

            /** @var ?string $group */
            $group = $result['grp'] ?? null;

            return new DataPoint(
                latitude: round((float) $lat, 5),
                longitude: round((float) $lon, 5),
                created: $time,
                group: $group,
            );
        }, $result);
    }
}
