<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EggGroup;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * @param string $slug
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function findOneBySlugWithRelation(string $slug) {
        return $this->createQueryBuilder('egg_group')
            ->select('egg_group', 'pokemon_species', 'pokemons', 'types', 'pokemon_sprites')
            ->join('egg_group.pokemonSpecies', 'pokemon_species')
            ->join('pokemon_species.pokemons', 'pokemons')
            ->join('pokemons.types', 'types')
            ->join('pokemons.pokemonSprites', 'pokemon_sprites')
            ->where('egg_group.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
