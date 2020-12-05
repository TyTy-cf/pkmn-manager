<?php


namespace App\Repository\Moves;


use App\Entity\Moves\PokemonMovesLearnVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PokemonMovesLearnVersionRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonMovesLearnVersion::class);
    }

    /**
     * @param string $slug
     * @return PokemonMovesLearnVersion|null
     * @throws NonUniqueResultException
     */
    public function getPokemonMovesLearnVersionByLanguageAndSlug
    (
        string $slug
    ): ?PokemonMovesLearnVersion
    {
        $qb = $this->createQueryBuilder('pokemon_moves_learn_version');
        $qb->where('pokemon_moves_learn_version.slug = :slug');
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return int|mixed|string
     */
    public function getLastPokemonIdInDataBase()
    {
        return $this->createQueryBuilder('pmlv')
            ->select('MAX(pmlv.pokemon)')
            ->getQuery()
            ->getResult();
    }
}