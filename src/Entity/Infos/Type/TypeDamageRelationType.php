<?php


namespace App\Entity\Infos\Type;


use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Type
 * @package App\Entity\Infos\Type
 *
 * @ORM\Table(name="type_damage_relation_type")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\Type\TypeDamageRelationTypeRepository")
 */
class TypeDamageRelationType
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
     * @var string $damageRelation
     * @ORM\Column(name="damage_relation", type="string", length=4)
     */
    private string $damageRelation;

    /**
     * @var Type $damageRelationType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Type\Type")
     * @ORM\JoinColumn(name="damage_relation_type_id", referencedColumnName="id")
     */
    private Type $damageRelationType;

    /**
     * @var float $damageRelationCoefficient
     * @ORM\Column(name="damage_relation_coefficient", type="float", length=2)
     */
    private float $damageRelationCoefficient;

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
    public function getDamageRelationType(): Type
    {
        return $this->damageRelationType;
    }

    /**
     * @param Type $damageRelationType
     */
    public function setDamageRelationType(Type $damageRelationType): void
    {
        $this->damageRelationType = $damageRelationType;
    }

    /**
     * @return float
     */
    public function getDamageRelationCoefficient(): float
    {
        return $this->damageRelationCoefficient;
    }

    /**
     * @param float $damageRelationCoefficient
     */
    public function setDamageRelationCoefficient(float $damageRelationCoefficient): void
    {
        $this->damageRelationCoefficient = $damageRelationCoefficient;
    }

    /**
     * @return string
     */
    public function getDamageRelation(): string
    {
        return $this->damageRelation;
    }

    /**
     * @param string $damageRelation
     */
    public function setDamageRelation(string $damageRelation): void
    {
        $this->damageRelation = $damageRelation;
    }

}