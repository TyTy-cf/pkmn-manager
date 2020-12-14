<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class MoveLearnMethod
 * @Entity
 * @ORM\Table(name="move_learn_method")
 */
class MoveLearnMethod
{

    const SLUG_MACHINE = "/move-learn-method-machine";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=6)
     */
    private int $id;

    use TraitNomenclature;

    use TraitDescription;

    /**
     * @var string $codeMethod
     * @ORM\Column(name="code_method", type="string", length=80)
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
     * @return string
     */
    public function getCodeMethod(): string
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

}