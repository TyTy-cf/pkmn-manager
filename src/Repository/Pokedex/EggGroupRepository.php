<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EggGroup;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EggGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method EggGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method EggGroup[]    findAll()
 * @method EggGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EggGroupRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EggGroup::class);
    }
}
