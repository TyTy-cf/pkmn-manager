<?php

namespace App\Repository\Stats;

use App\Entity\Stats\StatsEffort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatsEffort|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatsEffort|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatsEffort[]    findAll()
 * @method StatsEffort[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsEffortRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatsEffort::class);
    }

    /**
     * @param array $arrayStatsEffort
     * @return StatsEffort|null
     * @throws NonUniqueResultException
     */
    public function getStatsEffortByStats(array $arrayStatsEffort): ?StatsEffort
    {
        return $this->createQueryBuilder('stats_effort')
            ->select('stats_effort')
            ->where('stats_effort.hp = :hp')
            ->andWhere('stats_effort.atk = :atk')
            ->andWhere('stats_effort.def = :def')
            ->andWhere('stats_effort.spa = :spa')
            ->andWhere('stats_effort.spd = :spd')
            ->andWhere('stats_effort.spe = :spe')
            ->setParameter('hp', $arrayStatsEffort['hp'])
            ->setParameter('atk', $arrayStatsEffort['atk'])
            ->setParameter('def', $arrayStatsEffort['def'])
            ->setParameter('spa', $arrayStatsEffort['spa'])
            ->setParameter('spd', $arrayStatsEffort['spd'])
            ->setParameter('spe', $arrayStatsEffort['spe'])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
