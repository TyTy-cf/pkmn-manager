<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionChainChain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionChainChain|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionChainChain|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionChainChain[]    findAll()
 * @method EvolutionChainChain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionChainChainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionChainChain::class);
    }
}
