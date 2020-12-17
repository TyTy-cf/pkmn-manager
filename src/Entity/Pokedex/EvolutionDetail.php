<?php

namespace App\Entity\Pokedex;

use App\Entity\Infos\Gender;
use App\Entity\Infos\Type\Type;
use App\Entity\Items\Item;
use App\Entity\Locations\Location;
use App\Entity\Moves\Move;
use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\EvolutionDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="held_item_id", nullable=true)
     */
    private ?Item $heldItem;

    /**
     * Required used item to evolve
     * @var Item|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
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
     * Required a location to evolve
     * @var Location|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Locations\Location")
     * @JoinColumn(name="location_id", nullable=true)
     */
    private ?Location $location;

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
     * @ORM\Column(name="min_happiness", nullable=true, length=6)
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
     * @ORM\Column(type="smallint", nullable=true)
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
     * Required a specific pokemon species to trade with
     * @var PokemonSpecies|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="trade_species_id", nullable=true)
     */
    private ?PokemonSpecies $tradeSpecies;

    /**
     * Required a specific Type of pokemon in team to Evolve
     * @var EvolutionTrigger|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionTrigger")
     * @JoinColumn(name="evolution_trigger_id", nullable=true)
     */
    private ?EvolutionTrigger $evolutionTrigger;

    /**
     * @var string $otherDetailed
     * @ORM\Column(name="other_detailed", nullable=true)
     */
    private string $otherDetailed;

    /**
     * @var bool|null
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?bool $turnUpsideDown;

    private static string $levelUp = 'evolution-trigger-level-up';
    private static string $useItem = 'evolution-trigger-use-item';
    private static string $trade = 'evolution-trigger-trade';
    private static string $other = 'evolution-trigger-other';
    private static string $shed = 'evolution-trigger-shed';

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
     * @return EvolutionDetail
     */
    public function setGender(?Gender $gender): EvolutionDetail
    {
        $this->gender = $gender;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setHeldItem(?Item $heldItem): EvolutionDetail
    {
        $this->heldItem = $heldItem;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setUsedItem(?Item $usedItem): EvolutionDetail
    {
        $this->usedItem = $usedItem;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setKnownMove(?Move $knownMove): EvolutionDetail
    {
        $this->knownMove = $knownMove;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setKnownMoveType(?Type $knownMoveType): EvolutionDetail
    {
        $this->knownMoveType = $knownMoveType;
        return $this;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     * @return EvolutionDetail
     */
    public function setLocation(?Location $location): EvolutionDetail
    {
        $this->location = $location;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setMinAffection(?int $minAffection): EvolutionDetail
    {
        $this->minAffection = $minAffection;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setMinBeauty(?int $minBeauty): EvolutionDetail
    {
        $this->minBeauty = $minBeauty;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setMinHappiness(?int $minHappiness): EvolutionDetail
    {
        $this->minHappiness = $minHappiness;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setMinLevel(?int $minLevel): EvolutionDetail
    {
        $this->minLevel = $minLevel;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setNeedsOverworldRain(?bool $needsOverworldRain): EvolutionDetail
    {
        $this->needsOverworldRain = $needsOverworldRain;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setPokemonSpecies(?PokemonSpecies $pokemonSpecies): EvolutionDetail
    {
        $this->pokemonSpecies = $pokemonSpecies;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setPartyType(?Type $partyType): EvolutionDetail
    {
        $this->partyType = $partyType;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setTimeOfDay(?string $timeOfDay): EvolutionDetail
    {
        $this->timeOfDay = $timeOfDay;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setRelativePhysicalStats(?int $relativePhysicalStats): EvolutionDetail
    {
        $this->relativePhysicalStats = $relativePhysicalStats;
        return $this;
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
     * @return EvolutionDetail
     */
    public function setEvolutionTrigger(?EvolutionTrigger $evolutionTrigger): EvolutionDetail
    {
        $this->evolutionTrigger = $evolutionTrigger;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getTurnUpsideDown(): ?bool
    {
        return $this->turnUpsideDown;
    }

    /**
     * @param bool|null $turnUpsideDown
     * @return EvolutionDetail
     */
    public function setTurnUpsideDown(?bool $turnUpsideDown): EvolutionDetail
    {
        $this->turnUpsideDown = $turnUpsideDown;
        return $this;
    }

    /**
     * @return PokemonSpecies|null
     */
    public function getTradeSpecies(): ?PokemonSpecies
    {
        return $this->tradeSpecies;
    }

    /**
     * @param PokemonSpecies|null $tradeSpecies
     * @return EvolutionDetail
     */
    public function setTradeSpecies(?PokemonSpecies $tradeSpecies): EvolutionDetail
    {
        $this->tradeSpecies = $tradeSpecies;
        return $this;
    }

    /**
     * @return string
     */
    public function getOtherDetailed(): string
    {
        return $this->otherDetailed;
    }

    /**
     * @param string $otherDetailed
     * @return EvolutionDetail
     */
    public function setOtherDetailed(string $otherDetailed): EvolutionDetail
    {
        $this->otherDetailed = $otherDetailed;
        return $this;
    }

    /**
     * Display for EvolutionDetail
     */
    public function __toString()
    {
        // #1 : level up + level min
        if (strpos($this->evolutionTrigger->getSlug(), EvolutionDetail::$levelUp)) {
            $string = $this->evolutionTrigger->getTitle();
            if ($this->getMinLevel() !== null) {
                $string .=  ' ' . $this->getMinLevel();
            } else {
                $string = $this->evolutionTrigger->getName();
            }
            // #2 : level up + level min + gender
            if (isset($this->gender)) {
                $string = $this->gender->getName() . ', ' . strtolower($string);
            }
            if (isset($this->partyType)) {
                // knownMove + evolution trigger level up
                $string .= ' avec un Pokemon de type ' . $this->partyType->getName() . ' dans l\'équipe';
            }
            // #7 min beauty
            if ($this->needsOverworldRain) {
                // knownMove + evolution trigger level up
                $string .= ' dans un lieu avec de la pluie naturelle';
            }
            // heure de la journée
            if (!empty($this->timeOfDay)) {
                $day = $this->timeOfDay == 'day' ? ' le jour' : ' la nuit';
                $string = $string . $day;
            }
            // known move
            if (isset($this->knownMove)) {
                // knownMove + evolution trigger level up
                $string .= ' en ayant appris ' . $this->knownMove->getName();
            }
            // #6 known move type
            if (isset($this->knownMoveType)) {
                // knownMove + evolution trigger level up
                $string .= ' en ayant appris une attaque de type ' . $this->knownMoveType->getName() . ' et avec bonheur élevé';
            }
            // #6 known move type
            if (isset($this->relativePhysicalStats)) {
                if ($this->relativePhysicalStats === 1) {
                    $string .= ', si ATK > DEF';
                } else if ($this->relativePhysicalStats === -1) {
                    $string .= ', si ATK < DEF';
                } else {
                    $string .= ', si ATK = DEF';
                }
            }
            // #7 min beauty
            if (isset($this->minHappiness)) {
                $string .= ' avec bonheur élevé';
            }
            // #7 min beauty
            if (isset($this->minBeauty)) {
                $string .= ' avec un minimum ' . $this->minBeauty . ' de Beauté';
            }
            // #4 held item
            if (isset($this->heldItem)) {
                // held item + level up
                $string = $this->evolutionTrigger->getName() . ' en tenant ' . $this->heldItem->getName();
                if (!empty($this->timeOfDay)) {
                    $day = $this->timeOfDay == 'day' ? ' le jour' : ' la nuit';
                    $string .= $day;
                }
            }
            // #6 known move type
            if (isset($this->location)) {
                // knownMove + evolution trigger level up
                $string .= ' près de ' . $this->location->getName();
            }
            // #6 known move type
            if ($this->turnUpsideDown) {
                // knownMove + evolution trigger level up
                $string .= ' en ayant la console retournée';
            }
            return $string;
        }
        // #2 : use item
        if (strpos($this->evolutionTrigger->getSlug(), EvolutionDetail::$useItem)) {
            $string = $this->getUsedItem()->getName();
            if (isset($this->gender)) {
                $string = $this->gender->getName() . ', ' . $string;
            }
            return $string;
        }

        // #3 : trade
        if (strpos($this->evolutionTrigger->getSlug(), EvolutionDetail::$trade)) {
            $string = $this->evolutionTrigger->getName();
            if (isset($this->heldItem)) {
                $string .= ' en tenant ' . $this->heldItem->getName();
            }
            if (isset($this->tradeSpecies)) {
                $string .= ' contre ' . $this->tradeSpecies->getName();
            }
            return $string;
        }

        // #4 : shed
        if (strpos($this->evolutionTrigger->getSlug(), EvolutionDetail::$shed)) {
            return 'Niveau ' . $this->minLevel . ' et ' . $this->evolutionTrigger->getName();
        }

        // #4 : other
        if (strpos($this->evolutionTrigger->getSlug(), EvolutionDetail::$other)) {
            return $this->otherDetailed;
        }

        return '';
    }

}
