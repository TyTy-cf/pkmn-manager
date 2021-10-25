<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\Pokedex;
use App\Entity\Versions\Generation;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokedex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokedex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokedex[]    findAll()
 * @method Pokedex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokedexRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokedex::class);
    }

    /**
     * @param Generation $generation
     * @return int|mixed|string
     */
    public function getPokedexByGeneration(Generation $generation)
    {
        return $this->createQueryBuilder('pokedex')
            ->select('pokedex', 'versionGroup')
            ->join('pokedex.language', 'language')
            ->join('pokedex.generation', 'generation')
            ->leftJoin('pokedex.versionGroup', 'versionGroup')
            ->where('generation = :generation')
            ->setParameter('generation', $generation)
            ->getQuery()
            ->getResult()
        ;
    }
}
