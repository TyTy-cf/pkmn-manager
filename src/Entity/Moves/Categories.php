<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class Abilities *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="categories")
 * @Entity
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