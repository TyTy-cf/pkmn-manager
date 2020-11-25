<?php


namespace App\Entity\Moves;

use App\Entity\Infos\Type;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
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
 * @ORM\Entity(repositoryClass="App\Repository\Moves\MovesRepository")
 * @UniqueEntity(
 *     fields={"nameEn"},
 *     message="Cette attaque existe déjà !"
 * )
 */
class Moves
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

    use TraitDescription;

    use TraitLanguage;

    /**
     * @var Type $type the type of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Infos\Type")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     */
    private Type $type;

    /**
     * @var Categories $category the category of the move
     *
     * @ManyToOne(targetEntity="App\Entity\Moves\Categories")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    private Categories $category;

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
     * @return Categories
     */
    public function getCategory(): Categories
    {
        return $this->category;
    }

    /**
     * @param Categories $category
     */
    public function setCategory(Categories $category): void
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
     * @return Integer|null
     */
    public function getAccuracy(): ?Integer
    {
        return $this->accuracy;
    }

    /**
     * @param Integer|null $accuracy
     */
    public function setAccuracy(?Integer $accuracy): void
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
}