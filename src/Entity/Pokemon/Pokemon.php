<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Ability;
use App\Entity\Infos\Type\Type;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
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
 * @ORM\Table(name="pokemon")
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

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Ability", inversedBy="pokemons", cascade={"persist"})
     * @JoinTable(name="pokemon_abilities",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="ability_id", referencedColumnName="id")}
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
     * @ORM\Column(name="url_icon", type="string", length=255, nullable=true)
     */
    private ?string $urlIcon;

    /**
     * @ORM\Column(name="url_sprite_img", type="string", length=255, nullable=true)
     */
    private ?string $urlSpriteImg;

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
     * @param Ability $abilities
     */
    public function addAbilities(Ability $abilities): void
    {
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
}