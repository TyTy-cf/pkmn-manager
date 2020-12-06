<?php

namespace App\Entity\Pokemon;

use App\Entity\Traits\TraitDescription;
use App\Entity\Versions\Version;
use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=PokemonSpeciesVersionRepository::class)
 */
class PokemonSpeciesVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitDescription;

    /**
     * @var PokemonSpecies
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="pokemon_species_id")
     */
    private PokemonSpecies $pokemonSpecies;

    /**
     * @var Version
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\Version")
     * @JoinColumn(name="version_id")
     */
    private Version $version;

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
     * @return Version
     */
    public function getVersion(): Version
    {
        return $this->version;
    }

    /**
     * @param Version $version
     */
    public function setVersion(Version $version): void
    {
        $this->version = $version;
    }

}
