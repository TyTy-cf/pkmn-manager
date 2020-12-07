<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpeciesVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpeciesVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpeciesVersion[]    findAll()
 * @method PokemonSpeciesVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpeciesVersionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpeciesVersion::class);
    }

    // /**
    //  * @return PokemonSpeciesVersion[] Returns an array of PokemonSpeciesVersion objects
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
    public function findOneBySomeField($value): ?PokemonSpeciesVersion
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
