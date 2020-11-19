<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\TraitNames;
use App\Entity\Infos\Type;
use App\Entity\Moves\PokemonMovesLevel;
use App\Entity\Stats\TraitStatsPkmn;
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
 *     fields={"nameEn"},
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

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Abilities", inversedBy="pokemons", cascade={"persist"})
     * @JoinTable(name="pokemons_abilities",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="ability_id", referencedColumnName="id")}
     *      )
     */
    private ArrayCollection $abilities;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Type", inversedBy="pokemons", cascade={"persist"})
     * @JoinTable(name="pokemons_types",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="type_id", referencedColumnName="id")}
     *      )
     */
    private ArrayCollection $types;

    /**
     * @var PokemonMovesLevel $pokemonMovesLevel
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLevel", mappedBy="pokemon", fetch="EXTRA_LAZY")
     */
    private PokemonMovesLevel $pokemonMovesLevel;

    /**
     * @ORM\Column(name="url_img", type="string", length=255, nullable=true)
     */
    private ?string $urlImg;

    /**
     * Pokemon constructor.
     */
    public function __construct() {
        $this->abilities = new ArrayCollection();
        $this->types = new ArrayCollection();
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
    public function getUrlImg(): ?string
    {
        return $this->urlImg;
    }

    /**
     * @param string $urlImg
     */
    public function setUrlImg(?string $urlImg)
    {
        $this->urlImg = $urlImg;
    }

    /**
     * @param Abilities $abilities
     */
    public function addAbilities(Abilities $abilities): void
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
     * @param Abilities $ability
     */
    public function removeAbilities(Abilities $ability)
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
}