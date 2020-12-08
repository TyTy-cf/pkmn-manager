<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    /**
     * @param Language $language
     * @param int $offset
     * @param int $limit
     * @return array|int|string
     */
    public function getPokemonOffsetLimitByLanguage
    (
        Language $language,
        int $offset,
        int $limit
    )
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getPokemonAndSpeciesBySlug(string $slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'ps')
            ->leftJoin('p.pokemonSpecies', 'ps')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokemonNameForLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon.name')
            ->where('pokemon.language = :language')
            ->setParameter('language', $language)
            ->getQuery()
            ->getResult()
        ;
    }

}
