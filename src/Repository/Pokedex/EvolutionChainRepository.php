<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionChain;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionChain|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionChain|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionChain[]    findAll()
 * @method EvolutionChain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionChainRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionChain::class);
    }
}
