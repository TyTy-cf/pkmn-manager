<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Abilities *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="categories")
 * @Entity()
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette categorie existe déjà !"
 * )
 */
class Categories
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

    use TraitNames;

    use TraitLanguage;

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
}