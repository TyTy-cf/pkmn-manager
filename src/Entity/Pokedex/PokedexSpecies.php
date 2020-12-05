<?php

namespace App\Entity\Pokedex;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\PokedexSpeciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=PokedexSpeciesRepository::class)
 */
class PokedexSpecies
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
     * @var Pokedex
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\Pokedex")
     * @JoinColumn(name="pokedex_id")
     */
    private Pokedex $pokedex;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
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
     * @return Pokedex
     */
    public function getPokedex(): Pokedex
    {
        return $this->pokedex;
    }

    /**
     * @param Pokedex $pokedex
     */
    public function setPokedex(Pokedex $pokedex): void
    {
        $this->pokedex = $pokedex;
    }
}
