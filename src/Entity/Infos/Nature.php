<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Nature
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="nature")
 * @Entity
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette nature existe déjà !"
 * )
 */
class Nature
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @var string|null $statIncreased la stats "bonus" de la nature
     *
     * @ORM\Column(name="stat_increased", type="string", length=120, nullable=true)
     */
    private ?string $statIncreased;

    /**
     * @var string|null $statDecreased
     *
     * @ORM\Column(name="stat_decreased", type="string", length=120, nullable=true)
     */
    private ?string $statDecreased;

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
    public function getStatIncreased(): ?string
    {
        return $this->statIncreased;
    }

    /**
     * @param string|null $statIncreased
     * @return Nature
     */
    public function setStatIncreased(?string $statIncreased): self
    {
        $this->statIncreased = $statIncreased;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatDecreased(): ?string
    {
        return $this->statDecreased;
    }

    /**
     * @param string|null $statDecreased
     * @return Nature
     */
    public function setStatDecreased(?string $statDecreased): self
    {
        $this->statDecreased = $statDecreased;
        return $this;
    }

}