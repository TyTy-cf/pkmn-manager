<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Infos\GenderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class Gender *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="gender")
 * @Entity(repositoryClass=GenderRepository::class)
 */
class Gender
{

    /**
     * @var array|string[]
     */
    public static array $relationMap = [
        1 => 'gender-female',
        2 => 'gender-male',
        3 => 'gender-genderless',
    ];

    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @var string|null $image
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

}
