<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionDetail[]    findAll()
 * @method EvolutionDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionDetail::class);
    }

    // /**
    //  * @return EvolutionDetail[] Returns an array of EvolutionDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvolutionDetail
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
