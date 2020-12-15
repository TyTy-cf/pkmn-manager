<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionTrigger;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionTrigger|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionTrigger|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionTrigger[]    findAll()
 * @method EvolutionTrigger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionTriggerRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionTrigger::class);
    }
}
