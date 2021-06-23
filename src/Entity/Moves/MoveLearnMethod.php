<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class MoveLearnMethod
 * @Entity
 * @ORM\Table(name="move_learn_method")
 */
class MoveLearnMethod
{

    const CODE_MACHINE = "machine";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=6)
     */
    private int $id;

    use TraitNomenclature;

    use TraitDescription;

    /**
     * @var int $displayOrder
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    private int $displayOrder;

    /**
     * @var string|null $codeMethod
     * @ORM\Column(name="code_method", type="string", length=80, nullable=true)
     */
    private string $codeMethod;

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
    public function getCodeMethod(): ?string
    {
        return $this->codeMethod;
    }

    /**
     * @param string $codeMethod
     * @return MoveLearnMethod
     */
    public function setCodeMethod(string $codeMethod): self
    {
        $this->codeMethod = $codeMethod;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    /**
     * @param int $displayOrder
     * @return MoveLearnMethod
     */
    public function setDisplayOrder(int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }
}
