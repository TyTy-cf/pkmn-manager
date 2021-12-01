<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class TraitDisable.php
 *
 * @author Kevin Tourret
 */
Trait TraitDisable
{

    /**
     * @var bool isDisable
     *
     * @ORM\Column(name="is_disable", type="boolean", nullable=true)
     */
    private bool $isDisable;

    /**
     * @return bool
     */
    public function isDisable(): bool
    {
        return $this->isDisable;
    }

    /**
     * @param bool $isDisable
     * @return TraitDisable
     */
    public function setIsDisable(bool $isDisable): self
    {
        $this->isDisable = $isDisable;
        return $this;
    }

}
