<?php

namespace App\Entity\Pokemon;

use App\Entity\Pokedex\EggGroup;
use App\Entity\Pokedex\EvolutionChain;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Versions\Generation;
use App\Repository\Pokemon\PokemonSpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass=PokemonSpeciesRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(
 *          name="slug_idx",
 *          columns={"slug"}
 *     )
 * })
 */
class PokemonSpecies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $baseHappiness;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $captureRate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $growthRate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $hatchCounter;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private bool $isLegendary;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private bool $hasGenderDifferences;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private bool $isMythical;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private bool $isBaby;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $genera;

    /**
     * @ManyToMany(targetEntity="App\Entity\Pokedex\EggGroup", inversedBy="pokemonSpecies", cascade={"persist"})
     * @JoinTable(name="pokemon_species_eggs",
     *      joinColumns={@JoinColumn(name="pokemon_species_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="egg_group_id", referencedColumnName="id")}
 *      )
     */
    private Collection $eggGroup;

    /**
     * @var PokemonSpecies|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="evolves_from_species_id", nullable=true)
     */
    private ?PokemonSpecies $evolvesFromSpecies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pokemon\Pokemon", mappedBy="pokemonSpecies")
     */
    private Collection $pokemons;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pokedex\PokedexSpecies", mappedBy="pokemonSpecies")
     */
    private Collection $pokedexSpecies;

    /**
     * @var EvolutionChain|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionChain")
     * @JoinColumn(name="evolution_chain_id", nullable=true)
     */
    private ?EvolutionChain $evolutionChain;

    /**
     * PokemonSpecies constructor.
     */
    public function __construct()
    {
        $this->eggGroup = new ArrayCollection();
        $this->pokedexSpecies = new ArrayCollection();
        $this->pokemons = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBaseHappiness(): int
    {
        return $this->baseHappiness;
    }

    /**
     * @param int $baseHappiness
     * @return PokemonSpecies
     */
    public function setBaseHappiness(int $baseHappiness): self
    {
        $this->baseHappiness = $baseHappiness;
        return $this;
    }

    /**
     * @return int
     */
    public function getCaptureRate(): int
    {
        return $this->captureRate;
    }

    /**
     * @param int $captureRate
     * @return PokemonSpecies
     */
    public function setCaptureRate(int $captureRate): self
    {
        $this->captureRate = $captureRate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGrowthRate(): ?string
    {
        return $this->growthRate;
    }

    /**
     * @param string|null $growthRate
     * @return PokemonSpecies
     */
    public function setGrowthRate(?string $growthRate): self
    {
        $this->growthRate = $growthRate;
        return $this;
    }

    /**
     * @return int
     */
    public function getHatchCounter(): int
    {
        return $this->hatchCounter;
    }

    /**
     * @param int $hatchCounter
     * @return PokemonSpecies
     */
    public function setHatchCounter(int $hatchCounter): self
    {
        $this->hatchCounter = $hatchCounter;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLegendary(): bool
    {
        return $this->isLegendary;
    }

    /**
     * @param bool $isLegendary
     * @return PokemonSpecies
     */
    public function setIsLegendary(bool $isLegendary): self
    {
        $this->isLegendary = $isLegendary;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHasGenderDifferences(): bool
    {
        return $this->hasGenderDifferences;
    }

    /**
     * @param bool $hasGenderDifferences
     * @return PokemonSpecies
     */
    public function setHasGenderDifferences(bool $hasGenderDifferences): self
    {
        $this->hasGenderDifferences = $hasGenderDifferences;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMythical(): bool
    {
        return $this->isMythical;
    }

    /**
     * @param bool $isMythical
     * @return PokemonSpecies
     */
    public function setIsMythical(bool $isMythical): self
    {
        $this->isMythical = $isMythical;
        return $this;
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
     * @return PokemonSpecies
     */
    public function setIsBaby(bool $isBaby): self
    {
        $this->isBaby = $isBaby;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGenera(): ?string
    {
        return $this->genera;
    }

    /**
     * @param string|null $genera
     * @return PokemonSpecies
     */
    public function setGenera(?string $genera): self
    {
        $this->genera = $genera;
        return $this;
    }

    /**
     * @param EggGroup|null $eggGroup
     */
    public function addEggGroup(?EggGroup $eggGroup): void
    {
        if ($eggGroup === null) return;
        if (!$this->eggGroup->contains($eggGroup)) {
            $this->eggGroup->add($eggGroup);
        }
    }

    /**
     * @return mixed
     */
    public function getEggGroup(): Collection
    {
        return $this->eggGroup;
    }

    /**
     * @param EggGroup $eggGroup
     */
    public function removeEggGroup(EggGroup $eggGroup)
    {
        if ($this->eggGroup->contains($eggGroup)) {
            $this->eggGroup->removeElement($eggGroup);
        }
    }

    /**
     * @return PokemonSpecies|null
     */
    public function getEvolvesFromSpecies(): ?PokemonSpecies
    {
        return $this->evolvesFromSpecies;
    }

    /**
     * @param PokemonSpecies|null $evolvesFromSpecies
     * @return PokemonSpecies
     */
    public function setEvolvesFromSpecies(?PokemonSpecies $evolvesFromSpecies): self
    {
        $this->evolvesFromSpecies = $evolvesFromSpecies;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPokedexSpeciesCollection(): Collection
    {
        return $this->pokedexSpecies;
    }

    /**
     * @return Collection
     */
    public function getPokemons()
    {
        return $this->pokemons;
    }

    /**
     * @param Pokemon $pokemon
     */
    public function removePokemons(Pokemon $pokemon)
    {
        if ($this->pokemons->contains($pokemon)) {
            $this->pokemons->removeElement($pokemon);
        }
    }

    /**
     * @param Pokemon $pokemon
     */
    public function addPokemons(Pokemon $pokemon): void
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
        }
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPokedexSpecies()
    {
        return $this->pokedexSpecies;
    }

    /**
     * @return EvolutionChain|null
     */
    public function getEvolutionChain(): ?EvolutionChain
    {
        return $this->evolutionChain;
    }

    /**
     * @param EvolutionChain|null $evolutionChain
     * @return PokemonSpecies
     */
    public function setEvolutionChain(?EvolutionChain $evolutionChain): self
    {
        $this->evolutionChain = $evolutionChain;
        return $this;
    }

}
