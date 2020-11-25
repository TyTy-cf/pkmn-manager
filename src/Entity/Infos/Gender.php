<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class Gender *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="genre")
 * @Entity
 */
class Gender
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitNames;

    use TraitLanguage;

    /**
     * @var string $image
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private string $image;

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
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

}