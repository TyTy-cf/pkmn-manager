<?php


namespace App\Entity\Infos\Type;

use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Type
 * @package App\Entity\Infos\Type
 *
 * @ORM\Table(name="type_damage_from_type")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\Type\TypeDamageFromTypeRepository")
 */
class TypeDamageFromType
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitSlug;

    /**
     * @var Type $type
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private Type $type;

    /**
     * @var Type $damageFromType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @ORM\JoinColumn(name="damage_from_type_id", referencedColumnName="id")
     */
    private Type $damageFromType;

    /**
     * @var float $coefFrom
     * @ORM\Column(name="coef_from", type="float", length=2)
     */
    private float $coefFrom;

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
    public function getDamageFromType(): Type
    {
        return $this->damageFromType;
    }

    /**
     * @param Type $damageFromType
     */
    public function setDamageFromType(Type $damageFromType): void
    {
        $this->damageFromType = $damageFromType;
    }

    /**
     * @return float
     */
    public function getCoefFrom(): float
    {
        return $this->coefFrom;
    }

    /**
     * @param float $coefFrom
     */
    public function setCoefFrom(float $coefFrom): void
    {
        $this->coefFrom = $coefFrom;
    }
}