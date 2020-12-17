<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Repository\AbstractRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpeciesVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpeciesVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpeciesVersion[]    findAll()
 * @method PokemonSpeciesVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpeciesVersionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpeciesVersion::class);
    }

    /**
     * @param PokemonSpecies $pokemonSpecies
     * @return QueryBuilder
     */
    public function getDescriptionVersionByVersionsAndPokemon(PokemonSpecies $pokemonSpecies)
    {
        return $this->createQueryBuilder('psv')
            ->select('psv')
            ->join('psv.pokemonSpecies', 'pokemon_species')
            ->where('pokemon_species = :pokemonSpecies')
            ->setParameter('pokemonSpecies', $pokemonSpecies)
            ->getQuery()
            ->getResult()
        ;
    }
}
