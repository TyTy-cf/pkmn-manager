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
     * @return int|mixed|string
     */
    public function getDescriptionVersionByVersionsAndPokemon(PokemonSpecies $pokemonSpecies)
    {
        return $this->createQueryBuilder('psv')
            ->select('psv', 'version')
            ->join('psv.pokemonSpecies', 'pokemon_species')
            ->join('psv.version', 'version')
            ->join('version.versionGroup', 'version_group')
            ->where('pokemon_species = :pokemonSpecies')
            ->setParameter('pokemonSpecies', $pokemonSpecies)
            ->orderBy('version_group.order', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
