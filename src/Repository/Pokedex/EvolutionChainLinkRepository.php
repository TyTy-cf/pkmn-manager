<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionChain;
use App\Entity\Pokedex\EvolutionChainLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionChainLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionChainLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionChainLink[]    findAll()
 * @method EvolutionChainLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionChainLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionChainLink::class);
    }

    /**
     * @param EvolutionChain $evolutionChain
     * @return int|mixed|string
     */
    public function getEvolutionChainLinkByEvolutionChain(EvolutionChain $evolutionChain)
    {
        return $this->createQueryBuilder('ecl')
            ->select('ecl', 'evolution_detail')
            ->leftJoin('ecl.evolutionDetail', 'evolution_detail')
            ->join('ecl.evolutionChain', 'evolution_chain')
            ->where('evolution_chain = :evolutionChain')
            ->setParameter('evolutionChain', $evolutionChain)
            ->orderBy('ecl.evolutionOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
