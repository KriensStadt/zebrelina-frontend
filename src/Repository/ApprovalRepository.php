<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Approval;
use App\Entity\Device;
use App\Entity\TimePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Approval|null find($id, $lockMode = null, $lockVersion = null)
 * @method Approval|null findOneBy(array $criteria, array $orderBy = null)
 * @method Approval[]    findAll()
 * @method Approval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprovalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Approval::class);
    }

    /**
     * @return array<Approval>
     */
    public function findAutoDeletable(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.timePeriod', 't')

            ->andWhere('t.active = false')
            ->andWhere('t.periodEnd < :date')
            ->andWhere('a.approved = false')

            ->setParameter('date', $date)

            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return array<Approval>
     */
    public function findByActiveTimePeriods(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.timePeriod', 't')

            ->andWhere('t.active = true')
            ->andWhere('t.periodStart < :date')
            ->andWhere('t.periodEnd > :date')

            ->setParameter('date', $date)

            ->getQuery()
            ->execute()
            ;
    }

    public function findOneByDeviceAndTimePeriod(Device $device, TimePeriod $timePeriod): Approval
    {
        $existing = $this->createQueryBuilder('a')
            ->andWhere('a.device = :device')
            ->andWhere('a.timePeriod = :timePeriod')

            ->setParameter('device', $device)
            ->setParameter('timePeriod', $timePeriod)

            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $existing ?? (new Approval())
            ->setDevice($device)
            ->setTimePeriod($timePeriod)
        ;
    }
}
