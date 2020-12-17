<?php

namespace App\Repository\Pokedex;

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
     * @param EvolutionChainLink $evolutionChainLink
     * @return int|mixed|string
     */
    public function getEvolutionChainLinkChild(EvolutionChainLink $evolutionChainLink)
    {
        return $this->createQueryBuilder('ecl')
            ->select('ecl', 'evolutions_chain_links', 'evolution_detail')
            ->leftJoin('ecl.evolutionDetail', 'evolution_detail')
            ->join('ecl.evolutionsChainLinks', 'evolutions_chain_links')
            ->where('ecl.id = :eclParamId')
            ->setParameter('eclParamId', $evolutionChainLink->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
