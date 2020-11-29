<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
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

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=6)
     */
    private int $id;

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    use TraitDescription;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}