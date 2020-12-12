<?php

namespace App\Repository\Pokemon;

use App\Entity\Locations\Region;
use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonForm;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\VersionGroup;
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
     * @return int|mixed|string
     */
    public function getAllPokemonByLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->getQuery()
            ->getResult()
        ;
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
    public function getPokemonPofileBySlug(string $slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'ps', 'eg', 'se')
            ->leftJoin('p.pokemonSpecies', 'ps')
            ->leftJoin('ps.eggGroup', 'eg')
            ->leftJoin('p.statsEffort', 'se')
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

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokemonsListByLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'types')
            ->leftJoin('pokemon.pokemonSprites', 'pokemonSprites')
            ->join('pokemon.types', 'types')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Pokedex $pokedex
     * @return int|mixed|string
     */
    public function getPokemonsByPokedex(Pokedex $pokedex)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'types', 'sprites', 'pokemonSpecies', 'pokedexSpecies', 'pokemonForms')

            ->join('pokemon.types', 'types')
            ->join('pokemon.pokemonSprites', 'sprites')
            ->join('pokemon.pokemonSpecies', 'pokemonSpecies')
            ->join('pokemonSpecies.pokedexSpecies', 'pokedexSpecies')
            ->join('pokedexSpecies.pokedex', 'pokedex')
            ->leftJoin('pokemon.pokemonForms', 'pokemonForms')

            ->where('pokedex = :pokedex')
            ->andWhere('pokemon.isDefault = 1')

            ->setParameter('pokedex', $pokedex)

            ->orderBy('pokedexSpecies.number', 'ASC')

            ->getQuery()
            ->getResult()
        ;
    }

}
