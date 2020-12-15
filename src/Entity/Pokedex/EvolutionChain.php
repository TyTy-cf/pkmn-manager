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
     * @JoinColumn(name="from_pokemon_species_id")
     */
    private PokemonSpecies $fromPokemonSpecies;

    /**
     * @var EvolutionDetail
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionDetail")
     * @JoinColumn(name="evolution_detail_id")
     */
    private EvolutionDetail $evolutionDetail;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private bool $isBaby;


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
    public function getFromPokemonSpecies(): PokemonSpecies
    {
        return $this->fromPokemonSpecies;
    }

    /**
     * @param PokemonSpecies $currentPokemonSpecies
     */
    public function setFromPokemonSpecies(PokemonSpecies $currentPokemonSpecies): void
    {
        $this->fromPokemonSpecies = $currentPokemonSpecies;
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

    /**
     * @return bool
     */
    public function isBaby(): bool
    {
        return $this->isBaby;
    }

    /**
     * @param bool $isBaby
     * @return EvolutionChain
     */
    public function setIsBaby(bool $isBaby): self
    {
        $this->isBaby = $isBaby;
        return $this;
    }

}
