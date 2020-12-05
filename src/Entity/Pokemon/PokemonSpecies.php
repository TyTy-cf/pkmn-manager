<?php

namespace App\Entity\Pokemon;

use App\Entity\Pokedex\EggGroup;
use App\Repository\Pokemon\PokemonSpeciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=PokemonSpeciesRepository::class)
 */
class PokemonSpecies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $baseHappiness;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $captureRate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $growthRate;

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
     * @var EggGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EggGroup")
     * @JoinColumn(name="egg_group_id")
     */
    private EggGroup $eggGroup;

    /**
     * @var PokemonSpecies
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="evolves_from_species_id")
     */
    private PokemonSpecies $evolvesFromSpecies;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseHappiness(): ?int
    {
        return $this->baseHappiness;
    }

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
     */
    public function setCaptureRate(int $captureRate): void
    {
        $this->captureRate = $captureRate;
    }

    /**
     * @return int
     */
    public function getGrowthRate(): int
    {
        return $this->growthRate;
    }

    /**
     * @param int $growthRate
     */
    public function setGrowthRate(int $growthRate): void
    {
        $this->growthRate = $growthRate;
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
     */
    public function setHatchCounter(int $hatchCounter): void
    {
        $this->hatchCounter = $hatchCounter;
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
     */
    public function setIsLegendary(bool $isLegendary): void
    {
        $this->isLegendary = $isLegendary;
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
     */
    public function setHasGenderDifferences(bool $hasGenderDifferences): void
    {
        $this->hasGenderDifferences = $hasGenderDifferences;
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
     */
    public function setIsMythical(bool $isMythical): void
    {
        $this->isMythical = $isMythical;
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
     */
    public function setIsBaby(bool $isBaby): void
    {
        $this->isBaby = $isBaby;
    }

    /**
     * @return EggGroup
     */
    public function getEggGroup(): EggGroup
    {
        return $this->eggGroup;
    }

    /**
     * @param EggGroup $eggGroup
     */
    public function setEggGroup(EggGroup $eggGroup): void
    {
        $this->eggGroup = $eggGroup;
    }

    /**
     * @return PokemonSpecies
     */
    public function getEvolvesFromSpecies(): PokemonSpecies
    {
        return $this->evolvesFromSpecies;
    }

    /**
     * @param PokemonSpecies $evolvesFromSpecies
     */
    public function setEvolvesFromSpecies(PokemonSpecies $evolvesFromSpecies): void
    {
        $this->evolvesFromSpecies = $evolvesFromSpecies;
    }

}
