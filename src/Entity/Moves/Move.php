<?php


namespace App\Entity\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Move
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="move")
 * @ORM\Entity(repositoryClass="App\Repository\Moves\MoveRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette attaque existe déjà !"
 * )
 */
class Move
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    /**
     * @var Type|null $type the type of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @JoinColumn(name="type_id", referencedColumnName="id", nullable=true)
     */
    private ?Type $type;

    /**
     * @var DamageClass|null $damageClass the category of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Moves\DamageClass")
     * @JoinColumn(name="damage_class_id", referencedColumnName="id", nullable=true)
     */
    private ?DamageClass $damageClass;

    /**
     * @var int|null $pp the maximu number of point of power for this move
     * @ORM\Column(name="pp", type="integer", length=3, nullable=true)
     */
    private ?int $pp;

    /**
     * @var int|null $accuracy the accuracy of the move
     * @ORM\Column(name="accuracy", type="integer", length=3, nullable=true)
     */
    private ?int $accuracy;

    /**
     * @var int|null $power the power the of the move
     * @ORM\Column(name="power", type="integer", length=3, nullable=true)
     */
    private ?int $power;

    /**
     * @var int|null $priority the power the of the move
     * @ORM\Column(name="priority", type="smallint", length=1, nullable=true)
     */
    private ?int $priority;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLearnVersion", mappedBy="move", fetch="EXTRA_LAZY")
     */
    private Collection $pokemonMovesLearnVersion;

    public function __construct()
    {
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
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
     */
    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    /**
     * @return DamageClass|null
     */
    public function getDamageClass(): ?DamageClass
    {
        return $this->damageClass;
    }

    /**
     * @param DamageClass|null $damageClass
     */
    public function setDamageClass(?DamageClass $damageClass): void
    {
        $this->damageClass = $damageClass;
    }

    /**
     * @return int|null
     */
    public function getPp(): ?int
    {
        return $this->pp;
    }

    /**
     * @param int|null $pp
     */
    public function setPp(?int $pp): void
    {
        $this->pp = $pp;
    }

    /**
     * @return int|null
     */
    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    /**
     * @param int|null $accuracy
     */
    public function setAccuracy(?int $accuracy): void
    {
        $this->accuracy = $accuracy;
    }

    /**
     * @return int|null
     */
    public function getPower(): ?int
    {
        return $this->power;
    }

    /**
     * @param int|null $power
     */
    public function setPower(?int $power): void
    {
        $this->power = $power;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
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