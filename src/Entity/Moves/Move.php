<?php


namespace App\Entity\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
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

    use TraitNomenclature;

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
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
     * @return Move
     */
    public function setType(?Type $type): self
    {
        $this->type = $type;
        return $this;
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
     * @return Move
     */
    public function setDamageClass(?DamageClass $damageClass): self
    {
        $this->damageClass = $damageClass;
        return $this;
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
     * @return Move
     */
    public function setPp(?int $pp): self
    {
        $this->pp = $pp;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPpMax(): ?int
    {
        return $this->pp + (($this->pp) / 5) * 3;
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
     * @return Move
     */
    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;
        return $this;
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
     * @return Move
     */
    public function setPower(?int $power): self
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     * @return Move
     */
    public function setPriority(?int $priority): self
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