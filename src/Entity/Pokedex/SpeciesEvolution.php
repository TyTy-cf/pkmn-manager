<?php

namespace App\Entity\Pokedex;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\SpeciesEvolutionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=SpeciesEvolutionRepository::class)
 */
class SpeciesEvolution
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var PokemonSpecies
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\PokedexSpecies")
     * @JoinColumn(name="pokemon_species_id")
     */
    private PokemonSpecies $pokemonSpecies;

    /**
     * @var EvolutionChain
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionChain")
     * @JoinColumn(name="evolution_chain_id")
     */
    private EvolutionChain $evolutionChain;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return PokemonSpecies
     */
    public function getPokemonSpecies(): PokemonSpecies
    {
        return $this->pokemonSpecies;
    }

    /**
     * @param PokemonSpecies $pokemonSpecies
     */
    public function setPokemonSpecies(PokemonSpecies $pokemonSpecies): void
    {
        $this->pokemonSpecies = $pokemonSpecies;
    }

    /**
     * @return EvolutionChain
     */
    public function getEvolutionChain(): EvolutionChain
    {
        return $this->evolutionChain;
    }

    /**
     * @param EvolutionChain $evolutionChain
     */
    public function setEvolutionChain(EvolutionChain $evolutionChain): void
    {
        $this->evolutionChain = $evolutionChain;
    }

}
