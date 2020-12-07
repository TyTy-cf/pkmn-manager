<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitNomenclature;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Ability *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="damage_class")
 * @Entity()
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette categorie existe déjà !"
 * )
 */
class DamageClass
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @var string $image
     * @ORM\Column(name="image", type="string", length=255), nullable)
     */
    private string $image;

    use TraitNomenclature;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return DamageClass
     */
    public function setImage(string $image): DamageClass
    {
        $this->image = $image;
        return $this;
    }

}