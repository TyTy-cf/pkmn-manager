<?php


namespace App\Entity\Infos\Type;


use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Type *
 * @package App\Entity\Infos\Type
 *
 * @ORM\Table(name="type_damage_to_type")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\Type\TypeDamageToTypeRepository")
 */
class TypeDamageToType
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    use TraitSlug;

    /**
     * @var Type $type
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private Type $type;

    /**
     * @var Type $damageToType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @ORM\JoinColumn(name="damage_to_type_id", referencedColumnName="id")
     */
    private Type $damageToType;

    /**
     * @var float $coefTo
     * @ORM\Column(name="coef_to", type="float", length=2)
     */
    private float $coefTo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Type
     */
    public function getDamageToType(): Type
    {
        return $this->damageToType;
    }

    /**
     * @param Type $damageToType
     */
    public function setDamageToType(Type $damageToType): void
    {
        $this->damageToType = $damageToType;
    }

    /**
     * @return float
     */
    public function getCoefTo(): float
    {
        return $this->coefTo;
    }

    /**
     * @param float $coefTo
     */
    public function setCoefTo(float $coefTo): void
    {
        $this->coefTo = $coefTo;
    }
}