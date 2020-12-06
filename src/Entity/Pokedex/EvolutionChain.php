<?php

namespace App\Entity\Pokedex;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=EvolutionChainRepository::class)
 */
class EvolutionChain
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
     * @JoinColumn(name="evolve_to_pokemon_species_id")
     */
    private PokemonSpecies $evolveToPokemonSpecies;

    /**
     * @var PokemonSpecies
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\PokedexSpecies")
     * @JoinColumn(name="current_pokemon_species_id")
     */
    private PokemonSpecies $currentPokemonSpecies;

    /**
     * @var EvolutionChain
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokedex\EvolutionChain")
     * @JoinColumn(name="evolution_chain_id")
     */
    private EvolutionChain $evolutionChain;

    /**
     * @var EvolutionDetail
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionDetail")
     * @JoinColumn(name="evolution_detail_id")
     */
    private EvolutionDetail $evolutionDetail;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return PokemonSpecies
     */
    public function getEvolveToPokemonSpecies(): PokemonSpecies
    {
        return $this->evolveToPokemonSpecies;
    }

    /**
     * @param PokemonSpecies $evolveToPokemonSpecies
     */
    public function setEvolveToPokemonSpecies(PokemonSpecies $evolveToPokemonSpecies): void
    {
        $this->evolveToPokemonSpecies = $evolveToPokemonSpecies;
    }

    /**
     * @return PokemonSpecies
     */
    public function getCurrentPokemonSpecies(): PokemonSpecies
    {
        return $this->currentPokemonSpecies;
    }

    /**
     * @param PokemonSpecies $currentPokemonSpecies
     */
    public function setCurrentPokemonSpecies(PokemonSpecies $currentPokemonSpecies): void
    {
        $this->currentPokemonSpecies = $currentPokemonSpecies;
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

    /**
     * @return EvolutionDetail
     */
    public function getEvolutionDetail(): EvolutionDetail
    {
        return $this->evolutionDetail;
    }

    /**
     * @param EvolutionDetail $evolutionDetail
     */
    public function setEvolutionDetail(EvolutionDetail $evolutionDetail): void
    {
        $this->evolutionDetail = $evolutionDetail;
    }

}
