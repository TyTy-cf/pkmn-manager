<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
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
 * @ORM\Entity(repositoryClass="App\Repository\PokemonRepository")
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
    private $id;

    use TraitStatsPkmn;

    /**
     * @var string $name le nom du pokemon en anglais
     * @ORM\Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Abilities")
     * @JoinTable(name="pokemons_abilities",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="talent_id", referencedColumnName="id")}
     *      )
     */
    private $abilities;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Type")
     * @JoinTable(name="pokemons_types",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="type_id", referencedColumnName="id")}
     *      )
     */
    private $types;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlimg;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Abilities $abilities
     */
    public function addAbilities(Abilities $abilities): void
    {
        if ($this->abilities->contains($abilities)) {
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
     * @param Type $type
     */
    public function addTypes(Type $type): void
    {
        if ($this->types->contains($type)) {
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

    public function addAbility(Abilities $ability): self
    {
        if (!$this->abilities->contains($ability)) {
            $this->abilities[] = $ability;
        }

        return $this;
    }

    public function removeAbility(Abilities $ability): self
    {
        $this->abilities->removeElement($ability);

        return $this;
    }

    public function addType(Type $type): self
    {
        if(!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    public function getUrlimg(): ?string
    {
        return $this->urlimg;
    }

    public function setUrlimg(?string $urlimg): self
    {
        $this->urlimg = $urlimg;

        return $this;
    }
}