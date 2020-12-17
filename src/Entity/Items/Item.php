<?php

namespace App\Entity\Items;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Infos\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cost;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $spriteUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getSpriteUrl(): ?string
    {
        return $this->spriteUrl;
    }

    public function setSpriteUrl(?string $spriteUrl): self
    {
        $this->spriteUrl = $spriteUrl;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }


}
