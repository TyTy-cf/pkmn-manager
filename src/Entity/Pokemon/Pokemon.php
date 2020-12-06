<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Ability;
use App\Entity\Infos\Type\Type;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Stats\StatsEffort;
use App\Entity\Traits\TraitApi;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitSlug;
use App\Entity\Traits\TraitStatsPkmn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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
     * @ManyToMany(targetEntity="App\Entity\Infos\Ability", inversedBy="pokemons", cascade={"persist"})
     * @JoinTable(name="pokemon_abilities",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id", nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="ability_id", referencedColumnName="id", nullable=true)}
     *      )
     */
    private Collection $abilities;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Type\Type", inversedBy="pokemons", cascade={"persist"})
     * @JoinTable(name="pokemon_types",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="type_id", referencedColumnName="id")}
     *      )
     */
    private Collection $types;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLearnVersion", mappedBy="pokemon", fetch="EXTRA_LAZY")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="pokemon_species_id", nullable=true)
     */
    private PokemonSpecies $pokemonSpecies;

    /**
     * @ORM\Column(name="url_icon", type="string", length=255, nullable=true)
     */
    private ?string $urlIcon;

    /**
     * @ORM\Column(name="url_sprite_img", type="string", length=255, nullable=true)
     */
    private ?string $urlSpriteImg;

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
     * Pokemon constructor.
     */
    public function __construct() {
        $this->types = new ArrayCollection();
        $this->abilities = new ArrayCollection();
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getUrlIcon(): ?string
    {
        return $this->urlIcon;
    }

    /**
     * @param string|null $urlIcon
     */
    public function setUrlIcon(?string $urlIcon): void
    {
        $this->urlIcon = $urlIcon;
    }

    /**
     * @return string|null
     */
    public function getUrlSpriteImg(): ?string
    {
        return $this->urlSpriteImg;
    }

    /**
     * @param string|null $urlSpriteImg
     */
    public function setUrlSpriteImg(?string $urlSpriteImg): void
    {
        $this->urlSpriteImg = $urlSpriteImg;
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
     */
    public function setWeight(?int $weight): void
    {
        $this->weight = $weight;
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
     */
    public function setHeight(?int $height): void
    {
        $this->height = $height;
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
     */
    public function setStatsEffort(StatsEffort $statsEffort): void
    {
        $this->statsEffort = $statsEffort;
    }

    /**
     * @param Ability|null $abilities
     */
    public function addAbilities(?Ability $abilities): void
    {
        if ($abilities === null) return;
        if (!$this->abilities->contains($abilities)) {
            $this->abilities->add($abilities);
        }
    }

    /**
     * @return mixed
     */
    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    /**
     * @param Ability $ability
     */
    public function removeAbilities(Ability $ability)
    {
        if ($this->abilities->contains($ability)) {
            $this->abilities->removeElement($ability);
        }
    }

    /**
     * @param Type $type
     */
    public function addType(Type $type): void
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }
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
     */
    public function addPokemonMovesLearnVersion(PokemonMovesLearnVersion $pokemonMovesLearnVersion)
    {
        if (!$this->pokemonMovesLearnVersion->contains($pokemonMovesLearnVersion)) {
            $this->pokemonMovesLearnVersion[] = $pokemonMovesLearnVersion;
        }
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
     * @return PokemonSpecies
     */
    public function getPokemonSpecies(): PokemonSpecies
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

}