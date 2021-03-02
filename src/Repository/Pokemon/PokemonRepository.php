<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
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
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Pokemon::class);
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokemonByLanguage(Language $language) {
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
     * @return int|mixed|string
     */
    public function getAllPokemonNameForLanguage(Language $language) {
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
     * @param int $offset
     * @param int $limit
     * @return array|int|string
     */
    public function getPokemonOffsetLimitByLanguage(
        Language $language, int $offset, int $limit
    ) {
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
    public function getPokemonProfileBySlug(string $slug) {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'pokemon_species', 'egg_group', 'stats_effort', 'pokemon_sprites', 'types', 'pokemons_ability', 'ability')
            ->leftJoin('pokemon.pokemonSpecies', 'pokemon_species')
            ->join('pokemon.pokemonsAbility', 'pokemons_ability')
            ->join('pokemons_ability.ability', 'ability')
            ->leftJoin('pokemon_species.eggGroup', 'egg_group')
            ->leftJoin('pokemon.statsEffort', 'stats_effort')
            ->join('pokemon.pokemonSprites', 'pokemon_sprites')
            ->join('pokemon.types', 'types')
            ->andWhere('pokemon.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Pokedex $pokedex
     * @return int|mixed|string
     */
    public function getPokemonsByPokedex(Pokedex $pokedex) {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'types', 'sprites')
            ->join('pokemon.types', 'types')
            ->join('pokemon.pokemonSprites', 'sprites')
            ->join('pokemon.pokemonSpecies', 'pokemonSpecies')
            ->join('pokemonSpecies.pokedexSpecies', 'pokedexSpecies')
            ->join('pokedexSpecies.pokedex', 'pokedex')
            ->where('pokedex = :pokedex')
            ->andWhere('pokemon.isDefault = 1')
            ->setParameter('pokedex', $pokedex)
            ->orderBy('pokedexSpecies.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param PokemonSpecies $pokemonSpecies
     * @return int|mixed|string
     */
    public function getPokemonSpriteByPokemonSpecies(PokemonSpecies $pokemonSpecies) {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'pokemon_sprites')
            ->join('pokemon.pokemonSprites', 'pokemon_sprites')
            ->join('pokemon.pokemonSpecies', 'pokemon_species')
            ->where('pokemon_species = :pokemonSpecies')
            ->andWhere('pokemon.isDefault = 1')
            ->setParameter('pokemonSpecies', $pokemonSpecies)
            ->getQuery()
            ->getResult()
        ;
    }

}
