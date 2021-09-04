<?php

namespace App\Repository\Infos;

use App\Entity\Infos\Ability;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ability[]    findAll()
 * @method Ability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbilityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ability::class);
    }

    /**
     * @param Language $language
     * @return QueryBuilder
     */
    public function queryAll(Language $language): QueryBuilder
    {
        return $this->createQueryBuilder('ability')
            ->select('ability', 'pokemons_ability')
            ->join('ability.pokemonsAbility', 'pokemons_ability')
            ->where('ability.language = :language')
            ->setParameter('language', $language)
            ->orderBy('ability.name', 'ASC')
        ;
    }

    /**
     * @param string $slug
     * @return int|mixed|object|string|null
     * @throws NonUniqueResultException
     */
    public function findOneBySlugWithRelation(string $slug)
    {
        return $this->createQueryBuilder('ability')
            ->select('ability', 'pokemons_ability', 'pokemon', 'pokemon_sprites', 'types')
            ->join('ability.pokemonsAbility', 'pokemons_ability')
            ->join('pokemons_ability.pokemon', 'pokemon')
            ->join('pokemon.pokemonSprites', 'pokemon_sprites')
            ->join('pokemon.types', 'types')
            ->join('pokemon.pokemonSpecies', 'pokemonSpecies')
            ->join('pokemonSpecies.pokedexSpecies', 'pokedexSpecies')
            ->join('pokedexSpecies.pokedex', 'pokedex')
            ->where('ability.slug = :slug')
            ->andWhere('pokedex.name = :national')
            ->setParameter('slug', $slug)
            ->setParameter('national', 'National')
            ->orderBy('pokedexSpecies.number', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Pokemon $pokemon
     * @return Ability[]|null
     */
    public function getAbilitysByPokemon(Pokemon $pokemon): ?array {
        return $this->createQueryBuilder('ability')
            ->select('ability')
            ->join('ability.pokemonsAbility', 'pokemons_ability')
            ->join('pokemons_ability.pokemon', 'pokemon')
            ->where('pokemon = :pokemon')
            ->setParameter('pokemon', $pokemon)
            ->orderBy('ability.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
