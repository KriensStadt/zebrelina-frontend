<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Approval;
use App\Entity\Comment;
use App\Entity\DeviceGroup;
use App\Entity\TimePeriod;
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

        return $this->mapToDataPoints($result);
    }

    public function findDataPointsForGroupAndTimePeriod(DeviceGroup $deviceGroup, TimePeriod $timePeriod): array
    {
        $query = $this->createQueryBuilder('c')
            ->select([
                'c.createdAt',
                'c.content',
                'ST_X(c.point) AS lon',
                'ST_Y(c.point) AS lat'
            ])

            ->leftJoin('c.approval', 'a')
            ->leftJoin('a.device', 'd')
            ->leftJoin('d.deviceGroups', 'g')

            ->andWhere('a.timePeriod = :timePeriod')
            ->andWhere('a.approved = true')
            ->andWhere('g = :group')

            ->setParameter('timePeriod', $timePeriod)
            ->setParameter('group', $deviceGroup)
        ;

        $result = $query
            ->getQuery()
            ->getArrayResult()
        ;

        return $this->mapToDataPoints($result);
    }

    public function findGroupDataPointsForGroupAndTimePeriod(DeviceGroup $deviceGroup, TimePeriod $timePeriod): array
    {
        $query = $this->createQueryBuilder('c')
            ->select([
                'c.createdAt',
                'c.content',
                'ST_X(c.point) AS lon',
                'ST_Y(c.point) AS lat'
            ])

            ->andWhere('c.timePeriod = :timePeriod')
            ->andWhere('c.deviceGroup = :deviceGroup')

            ->setParameter('timePeriod', $timePeriod)
            ->setParameter('deviceGroup', $deviceGroup)
        ;

        $result = $query
            ->getQuery()
            ->getArrayResult()
        ;

        return $this->mapToDataPoints($result);
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
