<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\SpeciesEvolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpeciesEvolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpeciesEvolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpeciesEvolution[]    findAll()
 * @method SpeciesEvolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeciesEvolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpeciesEvolution::class);
    }

    // /**
    //  * @return SpeciesEvolution[] Returns an array of SpeciesEvolution objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpeciesEvolution
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
