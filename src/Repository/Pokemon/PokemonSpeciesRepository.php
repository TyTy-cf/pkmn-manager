<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpecies[]    findAll()
 * @method PokemonSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpeciesRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpecies::class);
    }

    /**
     * @param string $slug
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function getSimplePokemonSpeciesBySlug(string $slug)
    {
        return $this->createQueryBuilder('ps')
            ->select('ps')
            ->where('ps.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
