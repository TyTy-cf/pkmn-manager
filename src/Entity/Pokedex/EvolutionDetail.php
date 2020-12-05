<?php

namespace App\Entity\Pokedex;

use App\Entity\Infos\Gender;
use App\Entity\Infos\Item;
use App\Entity\Infos\Type\Type;
use App\Entity\Moves\Move;
use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\EvolutionDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass=EvolutionDetailRepository::class)
 */
class EvolutionDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Gender|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Gender")
     * @JoinColumn(name="gender_id", nullable=true)
     */
    private ?Gender $gender;

    /**
     * Required held item to evolve
     * @var Item|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Item")
     * @JoinColumn(name="held_item_id", nullable=true)
     */
    private ?Item $heldItem;

    /**
     * Required used item to evolve
     * @var Item|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Item")
     * @JoinColumn(name="used_item_id", nullable=true)
     */
    private ?Item $usedItem;

    /**
     * Required knwon item to evolve
     * @var Move|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Moves\Move")
     * @JoinColumn(name="known_move_id", nullable=true)
     */
    private ?Move $knownMove;

    /**
     * Required type move to evolve
     * @var Type|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @JoinColumn(name="known_move_type_id", nullable=true)
     */
    private ?Type $knownMoveType;

    /**
     * Required to be in a specific location to evolve
     * @var string|null
     * @ORM\Column(nullable=true)
     */
    private ?string $location;

    /**
     * Required a minimum affection to evolve
     * @var int|null
     * @ORM\Column(nullable=true)
     */
    private ?int $minAffection;

    /**
     * Required a minimum beauty to evolve
     * @var int|null
     * @ORM\Column(nullable=true)
     */
    private ?int $minBeauty;

    /**
     * Required a minimum happiness to evolve
     * @var int|null
     * @ORM\Column(nullable=true)
     */
    private ?int $minHappiness;

    /**
     * Required a minimum level to evolve
     * @var int|null
     * @ORM\Column(nullable=true)
     */
    private ?int $minLevel;

    /**
     * Required to world to rain
     * @var bool|null
     * @ORM\Column(nullable=true)
     */
    private ?bool $needsOverworldRain;

    /**
     * Required a specific pokemon species in team to Evolve
     * @var PokemonSpecies|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="pokemon_species_id", nullable=true)
     */
    private ?PokemonSpecies $pokemonSpecies;

    /**
     * Required a specific Type of pokemon in team to Evolve
     * @var Type|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @JoinColumn(name="party_type_id", nullable=true)
     */
    private ?Type $partyType;

    /**
     * Required a specific time of day to Evolve
     * @var string|null
     * @ORM\Column(nullable=true)
     */
    private ?string $timeOfDay;

    /**
     * Required a stat to be higher of lower Evolve
     * @var int|null
     * @ORM\Column(nullable=true)
     */
    private ?int $relativePhysicalStats;

    /**
     * Required a specific Type of pokemon in team to Evolve
     * @var EvolutionTrigger|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionTrigger")
     * @JoinColumn(name="evolution_trigger_id", nullable=true)
     */
    private ?EvolutionTrigger $evolutionTrigger;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Gender|null
     */
    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    /**
     * @param Gender|null $gender
     */
    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return Item|null
     */
    public function getHeldItem(): ?Item
    {
        return $this->heldItem;
    }

    /**
     * @param Item|null $heldItem
     */
    public function setHeldItem(?Item $heldItem): void
    {
        $this->heldItem = $heldItem;
    }

    /**
     * @return Item|null
     */
    public function getUsedItem(): ?Item
    {
        return $this->usedItem;
    }

    /**
     * @param Item|null $usedItem
     */
    public function setUsedItem(?Item $usedItem): void
    {
        $this->usedItem = $usedItem;
    }

    /**
     * @return Move|null
     */
    public function getKnownMove(): ?Move
    {
        return $this->knownMove;
    }

    /**
     * @param Move|null $knownMove
     */
    public function setKnownMove(?Move $knownMove): void
    {
        $this->knownMove = $knownMove;
    }

    /**
     * @return Type|null
     */
    public function getKnownMoveType(): ?Type
    {
        return $this->knownMoveType;
    }

    /**
     * @param Type|null $knownMoveType
     */
    public function setKnownMoveType(?Type $knownMoveType): void
    {
        $this->knownMoveType = $knownMoveType;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return int|null
     */
    public function getMinAffection(): ?int
    {
        return $this->minAffection;
    }

    /**
     * @param int|null $minAffection
     */
    public function setMinAffection(?int $minAffection): void
    {
        $this->minAffection = $minAffection;
    }

    /**
     * @return int|null
     */
    public function getMinBeauty(): ?int
    {
        return $this->minBeauty;
    }

    /**
     * @param int|null $minBeauty
     */
    public function setMinBeauty(?int $minBeauty): void
    {
        $this->minBeauty = $minBeauty;
    }

    /**
     * @return int|null
     */
    public function getMinHappiness(): ?int
    {
        return $this->minHappiness;
    }

    /**
     * @param int|null $minHappiness
     */
    public function setMinHappiness(?int $minHappiness): void
    {
        $this->minHappiness = $minHappiness;
    }

    /**
     * @return int|null
     */
    public function getMinLevel(): ?int
    {
        return $this->minLevel;
    }

    /**
     * @param int|null $minLevel
     */
    public function setMinLevel(?int $minLevel): void
    {
        $this->minLevel = $minLevel;
    }

    /**
     * @return bool|null
     */
    public function getNeedsOverworldRain(): ?bool
    {
        return $this->needsOverworldRain;
    }

    /**
     * @param bool|null $needsOverworldRain
     */
    public function setNeedsOverworldRain(?bool $needsOverworldRain): void
    {
        $this->needsOverworldRain = $needsOverworldRain;
    }

    /**
     * @return PokemonSpecies|null
     */
    public function getPokemonSpecies(): ?PokemonSpecies
    {
        return $this->pokemonSpecies;
    }

    /**
     * @param PokemonSpecies|null $pokemonSpecies
     */
    public function setPokemonSpecies(?PokemonSpecies $pokemonSpecies): void
    {
        $this->pokemonSpecies = $pokemonSpecies;
    }

    /**
     * @return Type|null
     */
    public function getPartyType(): ?Type
    {
        return $this->partyType;
    }

    /**
     * @param Type|null $partyType
     */
    public function setPartyType(?Type $partyType): void
    {
        $this->partyType = $partyType;
    }

    /**
     * @return string|null
     */
    public function getTimeOfDay(): ?string
    {
        return $this->timeOfDay;
    }

    /**
     * @param string|null $timeOfDay
     */
    public function setTimeOfDay(?string $timeOfDay): void
    {
        $this->timeOfDay = $timeOfDay;
    }

    /**
     * @return int|null
     */
    public function getRelativePhysicalStats(): ?int
    {
        return $this->relativePhysicalStats;
    }

    /**
     * @param int|null $relativePhysicalStats
     */
    public function setRelativePhysicalStats(?int $relativePhysicalStats): void
    {
        $this->relativePhysicalStats = $relativePhysicalStats;
    }

    /**
     * @return EvolutionTrigger|null
     */
    public function getEvolutionTrigger(): ?EvolutionTrigger
    {
        return $this->evolutionTrigger;
    }

    /**
     * @param EvolutionTrigger|null $evolutionTrigger
     */
    public function setEvolutionTrigger(?EvolutionTrigger $evolutionTrigger): void
    {
        $this->evolutionTrigger = $evolutionTrigger;
    }

}
