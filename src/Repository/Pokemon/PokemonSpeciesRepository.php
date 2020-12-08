<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\AbstractRepository;
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

}
