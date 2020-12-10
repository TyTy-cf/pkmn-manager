<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\Pokedex;
use App\Entity\Users\Language;
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
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokedexDetailed(Language $language)
    {
        $qb = $this->createQueryBuilder('pokedex')
            ->select('pokedex')
            ->where('pokedex.language = :language')
            ->andWhere('pokedex.name = :national')
            ->setParameter('national', 'National')
            ->setParameter('language', $language)
            ->getQuery()
            ->getResult()
        ;

        return array_merge($qb, $this->createQueryBuilder('pokedex')
            ->select('pokedex', 'versions', 'versionGroup', 'region')
            ->join('pokedex.region', 'region')
            ->leftJoin('pokedex.versionGroup', 'versionGroup')
            ->leftJoin('versionGroup.versions', 'versions')
            ->where('pokedex.language = :language')
            ->setParameter('language', $language)
            ->orderBy('versionGroup.generation', 'DESC')
            ->getQuery()
            ->getResult()
        );
    }
}
