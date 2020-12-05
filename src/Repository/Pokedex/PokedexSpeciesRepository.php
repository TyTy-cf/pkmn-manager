<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\PokedexSpecies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokedexSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokedexSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokedexSpecies[]    findAll()
 * @method PokedexSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokedexSpeciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokedexSpecies::class);
    }

    // /**
    //  * @return PokedexSpecies[] Returns an array of PokedexSpecies objects
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
    public function findOneBySomeField($value): ?PokedexSpecies
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
