<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpecies[]    findAll()
 * @method PokemonSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpeciesRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpecies::class);
    }

    // /**
    //  * @return PokemonSpecies[] Returns an array of PokemonSpecies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PokemonSpecies
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
