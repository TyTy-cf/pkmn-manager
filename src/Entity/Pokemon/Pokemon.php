<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Type\Type;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Stats\StatsEffort;
use App\Entity\Traits\TraitApi;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitStatsPkmn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Pokemon
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\Pokemon\PokemonRepository")
 * @ORM\Table(name="pokemon", indexes={
 *     @ORM\Index(
 *          name="slug_idx",
 *          columns={"slug"}
 *     )
 * })
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce pokémon existe déjà !"
 * )
 */
class Pokemon
{
    /**
     * @var int $id l'id du pkmn en bdd
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitStatsPkmn;

    use TraitNomenclature;

    use TraitApi;

    /**
     * @OneToMany(targetEntity="App\Entity\Infos\PokemonAbility", mappedBy="pokemon", cascade={"persist"})
     */
    private Collection $pokemonsAbility;

    /**
     * @OneToMany(targetEntity="App\Entity\Pokemon\PokemonForm", mappedBy="pokemon", cascade={"persist"})
     */
    private Collection $pokemonForms;

    /**
     * @var PokemonSprites $pokemonSprites
     * @OneToOne(targetEntity="App\Entity\Pokemon\PokemonSprites")
     * @JoinColumn(name="pokemon_sprites_id", referencedColumnName="id")
     */
    private PokemonSprites $pokemonSprites;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Type\Type", inversedBy="pokemons")
     * @JoinTable(name="pokemon_types",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="type_id", referencedColumnName="id")}
     *      )
     */
    private Collection $types;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLearnVersion", mappedBy="pokemon")
     */
    private Collection $pokemonMovesLearnVersion;

    /**
     * @var StatsEffort
     * @ORM\ManyToOne(targetEntity="App\Entity\Stats\StatsEffort")
     * @JoinColumn(name="stats_effort_id")
     */
    private StatsEffort $statsEffort;

    /**
     * @var PokemonSpecies
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies", inversedBy="pokemons")
     * @JoinColumn(name="pokemon_species_id", nullable=true)
     */
    private PokemonSpecies $pokemonSpecies;

    /**
     * @var int|null
     * @ORM\Column(name="weight", type="integer", length=6, nullable=true)
     */
    private ?int $weight;

    /**
     * @var int|null
     * @ORM\Column(name="height", type="integer", length=6, nullable=true)
     */
    private ?int $height;

    /**
     * @var int|null
     * @ORM\Column(name="base_experience", type="integer", length=12, nullable=true)
     */
    private ?int $baseExperience;

    /**
     * @var bool|null
     * @ORM\Column(name="is_default", type="smallint", nullable=true)
     */
    private ?bool $isDefault;

    /**
     * Pokemon constructor.
     */
    public function __construct() {
        $this->types = new ArrayCollection();
        $this->pokemonForms = new ArrayCollection();
        $this->pokemonsAbility = new ArrayCollection();
        $this->pokemonMovesLearnVersion = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return StatsEffort
     */
    public function getStatsEffort(): StatsEffort
    {
        return $this->statsEffort;
    }

    /**
     * @param StatsEffort $statsEffort
     * @return Pokemon
     */
    public function setStatsEffort(StatsEffort $statsEffort): self
    {
        $this->statsEffort = $statsEffort;
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
     * @param PokemonSpecies $pokemonSpecies
     * @return Pokemon
     */
    public function setPokemonSpecies(PokemonSpecies $pokemonSpecies): self
    {
        $this->pokemonSpecies = $pokemonSpecies;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int|null $weight
     * @return Pokemon
     */
    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     * @return Pokemon
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBaseExperience(): ?int
    {
        return $this->baseExperience;
    }

    /**
     * @param int|null $baseExperience
     * @return Pokemon
     */
    public function setBaseExperience(?int $baseExperience): Pokemon
    {
        $this->baseExperience = $baseExperience;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPokemonsAbility(): Collection
    {
        return $this->pokemonsAbility;
    }

    /**
     * @return mixed
     */
    public function getPokemonForms(): Collection
    {
        return $this->pokemonForms;
    }

    /**
     * @param Type $type
     * @return Pokemon
     */
    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @param Type $type
     */
    public function removeType(Type $type)
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }
    }

    /**
     * @return Collection|PokemonMovesLearnVersion[]
     */
    public function getPokemonMovesLearnVersion(): Collection
    {
        return $this->pokemonMovesLearnVersion;
    }

    /**
     * @param PokemonMovesLearnVersion $pokemonMovesLearnVersion
     * @return Pokemon
     */
    public function addPokemonMovesLearnVersion(PokemonMovesLearnVersion $pokemonMovesLearnVersion): self
    {
        if (!$this->pokemonMovesLearnVersion->contains($pokemonMovesLearnVersion)) {
            $this->pokemonMovesLearnVersion[] = $pokemonMovesLearnVersion;
        }
        return $this;
    }

    /**
     * @param PokemonMovesLearnVersion $pokemonMovesLearnVersion
     */
    public function removePokemonMovesLearnVersion(PokemonMovesLearnVersion $pokemonMovesLearnVersion)
    {
        if ($this->pokemonMovesLearnVersion->contains($pokemonMovesLearnVersion)) {
            $this->pokemonMovesLearnVersion->remove($pokemonMovesLearnVersion);
        }
    }

    /**
     * @return PokemonSprites
     */
    public function getPokemonSprites(): PokemonSprites
    {
        return $this->pokemonSprites;
    }

    /**
     * @param PokemonSprites $pokemonSprites
     * @return Pokemon
     */
    public function setPokemonSprites(PokemonSprites $pokemonSprites): self
    {
        $this->pokemonSprites = $pokemonSprites;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool|null $isDefault
     * @return Pokemon
     */
    public function setIsDefault(?bool $isDefault): Pokemon
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * Return the sum of stats
     * @return int
     */
    public function getTotalStats()
    {
        return ($this->hp + $this->atk + $this->def + $this->spa + $this->spd + $this->spe);
    }

    /**
     * @param int $iv
     * @param int $ev
     * @param int $level
     * @return float|int
     */
    public function getHpFormula(int $iv, int $ev, int $level) {
        return floor(0.01 * (2 * $this->hp + $iv + floor(0.25 * $ev)) * $level) + $level + 10;
    }

    /**
     * @param int $iv
     * @param int $ev
     * @param int $level
     * @param int $multNature
     * @param int $stats
     * @return float|int
     */
    public function getStatsFormula(int $iv, int $ev, int $level, int $multNature, int $stats) {
        return (floor(0.01 * (2 * $stats + $iv + floor(0.25 * $ev)) * $level) + 5) * $multNature;
    }

    public function getAtkFormula(int $iv, int $ev, int $level, int $multNature) {
        return $this->getStatsFormula($iv, $ev, $level, $multNature, $this->atk);
    }

    public function getDefFormula(int $iv, int $ev, int $level, int $multNature) {
        return $this->getStatsFormula($iv, $ev, $level, $multNature, $this->def);
    }

    public function getSpaFormula(int $iv, int $ev, int $level, int $multNature) {
        return $this->getStatsFormula($iv, $ev, $level, $multNature, $this->spa);
    }

    public function getSpdFormula(int $iv, int $ev, int $level, int $multNature) {
        return $this->getStatsFormula($iv, $ev, $level, $multNature, $this->spd);
    }

    public function getSpeFormula(int $iv, int $ev, int $level, int $multNature) {
        return $this->getStatsFormula($iv, $ev, $level, $multNature, $this->spe);
    }
}