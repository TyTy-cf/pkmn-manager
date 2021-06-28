<?php

namespace App\Repository\Infos;

use App\Entity\Infos\Ability;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
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
            ->where('ability.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
