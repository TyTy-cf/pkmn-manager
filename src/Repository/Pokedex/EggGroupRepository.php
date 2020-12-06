<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EggGroup;
use App\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    // /**
    //  * @return EggGroup[] Returns an array of EggGroup objects
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
    public function findOneBySomeField($value): ?EggGroup
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
