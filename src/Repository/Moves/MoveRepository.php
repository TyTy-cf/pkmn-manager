<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MoveRepository
 * @package App\Repository\Infos
 */
class MoveRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }

    /**
     * @param string $slug
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function getSimpleMoveBySlug(string $slug)
    {
        return $this->createQueryBuilder('move')
            ->select('move')
            ->where('move.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $slug
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function getMoveBySlugWithRelation(string $slug)
    {
        return $this->createQueryBuilder('move')
            ->select('move', 'type', 'pokemon_moves_learn_version', 'pokemon', 'types', 'pokemon_sprites', 'moves_description', 'versionGroup')
            ->join('move.type', 'type')
            ->join('move.movesDescription', 'moves_description')
            ->join('moves_description.versionGroup', 'versionGroup')
            ->join('move.pokemonMovesLearnVersion', 'pokemon_moves_learn_version')
            ->join('pokemon_moves_learn_version.pokemon', 'pokemon')
            ->join('pokemon.types', 'types')
            ->join('pokemon.pokemonSprites', 'pokemon_sprites')
            ->where('move.slug = :slug')
            ->setParameter('slug', $slug)
            ->groupBy('pokemon', 'versionGroup')
            ->orderBy('versionGroup.displayedOrder', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param array $pokemons
     * @return array
     */
    public function getMovesByPokemons(array $pokemons): array
    {
        return $this->createQueryBuilder('move')
            ->select('move', 'type', 'damage_class')
            ->join('move.type', 'type')
            ->join('move.damageClass', 'damage_class')
            ->join(PokemonMovesLearnVersion::class, 'pmlv', Join::WITH, 'pmlv.move = move')
            ->where('pmlv.pokemon IN (:pokemon)')
            ->setParameter('pokemon', $pokemons)
            ->groupBy('move.name')
            ->orderBy('move.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
