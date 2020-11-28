<?php


namespace App\Entity\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Abilities *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="moves")
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

    use TraitDescription;

    use TraitLanguage;

    /**
     * @var Type $type the type of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     */
    private Type $type;

    /**
     * @var DamageClass $category the category of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Moves\DamageClass")
     * @JoinColumn(name="damage_class_id", referencedColumnName="id")
     */
    private DamageClass $damageClass;

    /**
     * @var int $pp the maximu number of point of power for this move
     * @ORM\Column(name="pp", type="integer", length=3)
     */
    private int $pp;

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
     * @ORM\Column(name="priority", type="smallint", length=1)
     */
    private ?int $priority;

    /**
     * @var PokemonMovesLevel $pokemonMovesLevel
     *
     * @ORM\OneToMany(targetEntity="PokemonMovesLevel", mappedBy="move", fetch="EXTRA_LAZY")
     */
    private PokemonMovesLevel $pokemonMovesLevel;

    public function __construct()
    {
//        $this->pokemonMovesLevel = new ArrayCollection();
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
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
    }

    /**
     * @return DamageClass
     */
    public function getCategory(): DamageClass
    {
        return $this->category;
    }

    /**
     * @param DamageClass $category
     */
    public function setCategory(DamageClass $category): void
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getPp(): int
    {
        return $this->pp;
    }

    /**
     * @param int $pp
     */
    public function setPp(int $pp): void
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
     * @return Integer|null
     */
    public function getPower(): ?Integer
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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
//
//    /**
//     * @return Collection|PokemonMovesLevel[]
//     */
//    public function getPokemonMovesLevel(): Collection
//    {
//        return $this->pokemonMovesLevel;
//    }
//
//    public function addPokemonMovesLevel(PokemonMovesLevel $pokemonMovesLevel): self
//    {
//        if (!$this->pokemonMovesLevel->contains($pokemonMovesLevel)) {
//            $this->pokemonMovesLevel[] = $pokemonMovesLevel;
//            $pokemonMovesLevel->setMove($this);
//        }
//
//        return $this;
//    }
//
//    public function removePokemonMovesLevel(PokemonMovesLevel $pokemonMovesLevel): self
//    {
//        if ($this->pokemonMovesLevel->removeElement($pokemonMovesLevel)) {
//            // set the owning side to null (unless already changed)
//            if ($pokemonMovesLevel->getMove() === $this) {
//                $pokemonMovesLevel->setMove(null);
//            }
//        }
//
//        return $this;
//    }
}