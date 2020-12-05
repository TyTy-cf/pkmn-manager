<?php

namespace App\Entity\Infos;

use App\Entity\Pokemon\Pokemon;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use App\Repository\Infos\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
    private $id;

    use TraitNomenclature;

    use TraitDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $flingPower;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flingEffect;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spriteUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon")
     * @JoinColumn(name="held_by_pokemon_id", nullable=true)
     */
    private $heldByPokemon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlingPower(): ?int
    {
        return $this->flingPower;
    }

    public function setFlingPower(?int $flingPower): self
    {
        $this->flingPower = $flingPower;

        return $this;
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

    public function getHeldByPokemon(): ?Pokemon
    {
        return $this->heldByPokemon;
    }

    public function setHeldByPokemon(?Pokemon $heldByPokemon): self
    {
        $this->heldByPokemon = $heldByPokemon;

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

    public function getFlingEffect(): ?string
    {
        return $this->flingEffect;
    }

    public function setFlingEffect(?string $flingEffect): self
    {
        $this->flingEffect = $flingEffect;

        return $this;
    }
}
