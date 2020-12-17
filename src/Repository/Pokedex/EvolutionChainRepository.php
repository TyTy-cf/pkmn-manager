<?php

namespace App\Repository\Pokedex;

use App\Entity\Pokedex\EvolutionChain;
use App\Entity\Pokemon\Pokemon;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvolutionChain|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvolutionChain|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvolutionChain[]    findAll()
 * @method EvolutionChain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvolutionChainRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvolutionChain::class);
    }

    /**
     * @param Pokemon $pokemon
     * @return EvolutionChain|null
     * @throws NonUniqueResultException
     */
    public function getEvolutionChainByPokemon(Pokemon $pokemon): ?EvolutionChain
    {
        return $this->createQueryBuilder('evolution_chain')
            ->select('evolution_chain', 'evolution_chain_link', 'current_pokemon_species', 'evolution_detail', 'evolutions_chain_links')
            ->join('evolution_chain.evolutionChainLink', 'evolution_chain_link')
            ->join('evolution_chain_link.currentPokemonSpecies', 'current_pokemon_species')
            ->leftJoin('evolution_chain_link.evolutionDetail', 'evolution_detail')
            ->leftJoin('evolution_chain_link.evolutionsChainLinks', 'evolutions_chain_links')
            ->where('evolution_chain = :evolutionChain')
            ->setParameter('evolutionChain', $pokemon->getPokemonSpecies()->getEvolutionChain())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
