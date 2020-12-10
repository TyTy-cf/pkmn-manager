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
        return $this->createQueryBuilder('pokedex')
            ->select('pokedex.description', 'pokedex.name', 'pokedex.slug')
            ->where('pokedex.language = :language')
            ->setParameter('language', $language)
            ->getQuery()
            ->getResult()
            ;
    }
}
